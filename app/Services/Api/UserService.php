<?php

namespace App\Services\Api;

use App\Exceptions\Api;
use App\Exceptions\Api\Exception;
use App\Services\BaseService;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;
use App\Services\Library\FileManipulation;
use App\Services\Library\ServiceData;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Support\Facades\Auth;


class UserService extends BaseService
{

    public function login($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'email' => 'required|email|exists:users',
                'password' => 'required',
            ], true);

            $user = User::where('email', $params['email'])->first();

            if (!Hash::check($params['password'], $user->password)){
                throw new Exception('Usuário ou senha Incorreta');
            }
            
            $user->lastAccess = Carbon::now()->toDateTimeString();
            $user->save();

            return $data->setData([
                'token' => $user->createToken(env('API_TOKEN_NAME'))->accessToken,
            ])->setMessage('Login realizado com sucesso');
        });
    }

    public function register($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'name' => 'required',
                'email' => 'required|email|unique:Users',
                'password' => 'required',
                'cPassword' => 'required|same:password',
            ], true);

            $params['password'] = bcrypt($params['password']);

            $user = User::create($params);

            if (!empty($params['roleNames'])){
                $user->syncRoles($params['roleNames']);
            } else {
                $user->syncRoles([]);
            }

            return $data->setData([
                'user' => $user
            ])->setMessage('Usuário cadastrado com sucesso');
        });
    }

    public function details($id)
    {
        return $this->holdMistake(function() use ($id){
   
            $user = User::find($id);
            if (!$user){
                throw new RecordNotFoundException("Usuário não encontrado");
            }
            return (new ServiceData($user))->setMessage("dados do usuário");
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){

            $users = [];

            foreach (User::all() as $user) {
                $users[] = [
                   'id' => $user->id,
                   'name' => $user->name,
                   'email' => $user->email,
                   'lastAccess' => $user->lastAccess,
                   'roleNames' => $user->getRoleNames()
                ];
            }

            return (new ServiceData($users))->setMessage('Usuários recuperados com sucesso');
        });
    }

    public function autocomplete()
    {
        return $this->holdMistake(function(){
            $users = [];
            foreach (User::all() as $user) {
                $users[] = [
                   'id' => $user->id,
                   'name' => $user->name,
                ];
            }
            return (new ServiceData($users))->setMessage('Usuários recuperados com sucesso');
        });
    }

    public function destroy($id)
    {
        return $this->transaction(function() use ($id) {
            $user = User::find($id);
            if (!$user) {
                throw new RecordNotFoundException("Usuário não encontrado");
            }
            $user->delete();
            return (new ServiceData($user))->setMessage('Usuário apagado com sucesso');
        });
    }

    private function updateDataUser($params)
    {
        $user = User::find($params['id']);

        if (!empty($params['avatar'])){
            $avatarFileId = $user->AvatarFileId;

            if ($avatarFileId){
                $user->AvatarFileId = null; //remove reference
                $user->save();

                $this->holdMistake(function() use ($user){
                    $user->deleteFile($user->AvatarFileId, false);
                });
            }

           $params['AvatarFileId'] = $user->saveFile($params['avatar'])->FileId;
        }

        if (isset($params['oldPassword'])  && !Hash::check($params['oldPassword'], $user->password)){
            throw new Exception("Senha antiga informada não confere");
        }

        if (!empty($params['password'])){
            $params['password'] = bcrypt($params['password']); //is update password
        } else {
            unset($params['password']); //no update password
        }

        unset($params['avatar']);
        $user->fill($params);

        if (isset($params['roleNames'])){
            $user->syncRoles($params['roleNames']);
        }

        return $user->save() ? $user : null;
    }

    public function update($params)
    {
        return $this->transaction(function() use ($params) {

            $validate = $this->validate($params, [
                'id' => 'required',
                'name' => 'required',
                'email' => 'required|email|exists:users'
            ], true, function($params){

                if (isset($params['password'])){
                    return [
                        'password' => 'required|min:6',
                        'cPassword' => 'required|same:password'
                    ];
                }
            });

            $user = $this->updateDataUser($params);
            $user->urlAvatar = $this->getUrlAvatarUser($user->id); //set url current avatar

            return $validate->setData($user)->setMessage('Usuário Atualizado com sucesso');
        });
    }

    public function getUrlAvatarUser($userId)
    {
        return $this->holdMistake(function() use ($userId){
            $user = User::find($userId);

            if ($user->AvatarFileId){
                return $user->generateUrl($user->AvatarFileId);
            }

            return null;
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'id',
            'name',
            'email',
            'lastAccess'
        ];

        return User::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'id' => 'id',
                'name' => 'name',
                'email' => 'email',
                'lastAccess' => 'lastAccess'
            ]);

            $response = new ResponseCommonDatatable();

            $response->fillDefaultDataTable($dataTableFilter, $query, function($originalData, $data){
                $data['roleNames'] = $originalData->getRoleNames();
                return $data;
            });

            return new ServiceData($response);
        });
    }
}
