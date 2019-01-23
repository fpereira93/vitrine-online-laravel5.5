<?php

namespace App\Services\Api;

use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Models\Training;
use App\Models\TrainingTopic;
use App\Models\UserTraining;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class TrainingService extends BaseService
{
    public function create($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'TrainerId' => 'required',
                'Place' => 'required',
                'Theme' => 'required',
                'BeginDate' => 'required',
            ], true);

            $params['Status'] = 1;

            $training = Training::firstOrCreate($params);
            return $data->setData($training)->setMessage('Treinamento criado com sucesso');
        });
    }

    public function update($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'TrainingId' => 'required|exists:Trainings',
                'TrainerId' => 'required',
                'Place' => 'required',
                'Theme' => 'required',
                'BeginDate' => 'required',
                'Status' => 'required'
            ], true);

            $training = Training::find($params['TrainingId']);
            if (!$training) {
                throw new RegisterNotFoundException("Não existe um treinamento com esses dados");
            }
            $training->fill($params);
            $training->save();

            return $data->setData($training)->setMessage('Treinamento atualizado com sucesso');
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){

            if (!Training::destroy($id)){
                throw new NotAllowedDeletionException('Não foi possível apagar o treinamento, verifique se ele já possui alguma dependencia');
            }
            return new ServiceData(true); 
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            return new ServiceData(Training::all());
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'TrainingId',
            'Place',
            'Theme',
            'BeginDate'
        ];

        return Training::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'TrainingId' => 'TrainingId',
                'Place' => 'Place',
                'Theme' => 'Theme',
                'BeginDate' => 'BeginDate'
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

    public function get($trainingId)
    {
        return $this->holdMistake(function() use ($trainingId){
            $training = Training::find($trainingId);
            if (!$training) {
                throw new RegisterNotFoundException("Não existe um treinamento com esse código");
            }
            $training->topics;
            $training->trainer;
            return new ServiceData($training);
        });
    }

    public function syncTopics($data, $trainingId)
    {
        return $this->transaction(function() use ($data, $trainingId) {
            foreach ($data['topics'] as $topic) {

                if (!empty($topic['TrainingTopicId'])){
                    if ($topic['Deleted'] == 'true'){
                        TrainingTopic::destroy($topic['TrainingTopicId']);
                    } else {

                        $t = TrainingTopic::find($topic['TrainingTopicId']);
                        $t->fill($topic);
                        $t->save();

                    }
                } else {
                    $t = new TrainingTopic();
                    $t->fill($topic);
                    $t->TrainingId = $trainingId;
                    $t->save();
                }
            }
            $response = new ServiceData(array());
            return $response->setMessage('Tópicos adicionados com sucesso');
        });
    }

    public function addUser($data, $id)
    {
        return $this->transaction(function() use($data, $id) {
            $users = UserTraining::where('TrainingId', $id)->where('id', $data['id'])->first();
            if ($users) {
                throw new DuplicateDataException('Esse usuário já está nesse treinamento!');
            }
            $userTraining = new UserTraining(array_merge($data, ['TrainingId' => $id]));
            $userTraining->save();
            $response = new ServiceData($userTraining->user);
            return $response->setMessage('Usuário adicionado com sucesso!');
        });
    }

    public function removeUser($data, $id)
    {
        return $this->transaction(function() use($data, $id) {
            $user = UserTraining::Where('TrainingId', $id)->where('id', $data['id'])->first();
            if (!$user) {
                throw new RegisterNotFoundException("Esse usuário não existe nesse treinamento");
            }
            $user->delete();
            $response = new ServiceData(array());
            return $response->setMessage('Usuário removido com sucesso');
        });
    }

    public function summoneds($trainingId)
    {
        return $this->holdMistake(function() use ($trainingId){
            $training = Training::find($trainingId);
            if (!$training) {
                throw new RegisterNotFoundException("Não existe um treinamento com esse código");
            }
            return new ServiceData($training->users);
        });
    }
}
