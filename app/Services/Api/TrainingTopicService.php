<?php

namespace App\Services\Api;

use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Models\Trainer;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class TrainingTopicService extends BaseService
{
    public function create($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'Description' => 'required',
            ], true);

            $provider = new TrainingTopic;
            $provider->fill($params);
            $provider->save();

            return $data->setData($provider)->setMessage('Tópico criado com sucesso');
        });
    }

    public function update($params)
    {
         return $this->transaction(function() use ($params){
             $data = $this->validate($params, [
                 'TrainingTopicId' => 'required|exists:TrainingTopics',
                 'Description' => 'required',
             ], true);

            $trainingTopic = TrainingTopic::where("TrainingTopicId", "<>", $params['TrainingTopicId'])
            ->where("Description", $params['Description'])->first();

            if ($trainingTopic){
                throw new DuplicateDataException('Já existe Tópico com estes dados');
            }

            $trainingTopicUpdate = TrainingTopic::find($params['TrainingTopicId']);
            $trainingTopicUpdate->Description = $params['Description'];
            $trainingTopicUpdate->save();

            return $data->setData($trainingTopicUpdate)->setMessage('Tópico atualizado com sucesso');
        });
    }

    public function delete(int $id)
    {
         return $this->transaction(function() use ($id) {
             if (!TrainingTopic::destroy($id)){
                 throw new NotAllowedDeletionException('Não foi possível apagar o tópico, verifique se ele possui alguma dependência.');
             }
             return new ServiceData(true); 
         });
    }

    public function all()
    {
        return $this->holdMistake(function() {
            return new ServiceData(TrainingTopic::all());
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'TrainingTopicId',
            'Description'
        ];

        return TrainingTopic::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'TrainingTopicId' => 'TrainingTopicId',
                'Description' => 'Description'
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

}
