<?php

namespace App\Services\Api;

use App\Services\BaseService;
use App\Exceptions\Api\Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;
use App\Services\Library\ServiceData;

/**
 * https://github.com/spatie/laravel-permission/blob/master/README.md
 */

class PermissionService extends BaseService
{

    private function firstOrCreateRole($roleName)
    {   
        return Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => config('auth.defaults.guard')
        ]);
    }

    private function firstOrCreatePermissions($permissionArray)
    {
        return array_map(function($permission){
            return Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => config('auth.defaults.guard')
            ]);
        }, $permissionArray);
    }

    private function addRolesAndPermissionsIntoDb($dataRolesArray)
    {
        $result = [];

        foreach ($dataRolesArray as $key => $roleData) {
            $roleDb = null;
            $permissionsDb = $this->firstOrCreatePermissions($roleData['permissions']);

            if (!empty($roleData['role'])){
                $roleDb = $this->firstOrCreateRole($roleData['role']);
                $roleDb->syncPermissions($roleData['permissions']);
            }

            $result[$key]['role'] = $roleDb;
            $result[$key]['permissions'] = $permissionsDb;
        }

        return $result;
    }

    /**
     * [syncPermissions 'Sync Permissions']
        { // Exemplo de uso
            "data" : [
                {
                    "role": "super-admin",
                    "permissions": [
                        "permission1", "permission2"
                    ]
                }
            ]
        }
     */
    public function syncPermissions($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'data.*.role' => 'string',
                'data.*.permissions' => 'array',
                'data.*.permissions.*' => 'string',
            ], true);

            $permissionsDb = $this->addRolesAndPermissionsIntoDb($params['data']);

            return $data->setData($permissionsDb)
                ->setMessage('Permissões atualizadas com sucesso');
        });
    }

    /**
     * [syncPermissionsUser 'Sync Permission User ']
        { // Exemplo de uso
            "userId": 1,
            "data" : {
                "roles": [
                    "super-admin"
                ],
                "permissions": [
                    "permission1", "permission2"
                ]
            }
        }
     */
    public function syncPermissionsUser($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'data.roles' => 'array',
                'data.permissions' => 'array',
                'data.roles.*' => 'string',
                'data.permissions.*' => 'string',
                'userId' => 'required|integer|exists:users,id',
            ], true);

            $userDb = User::find($params['userId']);

            $userDb->syncRoles($params['data']['roles']);
            $userDb->syncPermissions($params['data']['permissions']);

            return $data->setData([
                'userAffected' => $userDb
            ])->setMessage('Permissões atualizadas com sucesso');
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            return new ServiceData(Role::all());
        });
    }

    public function rolesUser($userId)
    {
        return $this->holdMistake(function() use ($userId){
            $user = User::find($userId);

            if (!$user)
                throw new Exception('Usuário não encontrado');

            return new ServiceData([
                'roles' => $user->getRoleNames(),
                'permissions' => array_map(function($permission){
                    return $permission['name'];
                }, $user->getAllPermissions()->toArray()),
            ]);
        });
    }
}
