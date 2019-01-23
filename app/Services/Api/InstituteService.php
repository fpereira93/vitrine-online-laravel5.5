<?php

namespace App\Services\Api;

use DB;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Models\Institute;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class InstituteService extends BaseService
{
    private $sectorService = null;

    function __construct() 
    {
        $this->sectorService = new SectorService();
    }

    public function create($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'Name' => 'required',
            ], true);

            if (Institute::where('Name', $params['Name'])->count() > 0) {
                throw new DuplicateDataException('Já existe Instituto com estes dados');
            }

            $institute = Institute::firstOrCreate(['Name' => $params['Name']]);

            return $data->setData($institute)->setMessage('Instituto criado com sucesso');
        });
    }

    public function update($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'InstituteId' => 'required|exists:Institutes',
                'Name' => 'required',
            ], true);

            $institute = Institute::where("InstituteId", "<>", $params['InstituteId'])
            ->where("Name", $params['Name'])->first();

            if ($institute){
                throw new DuplicateDataException('Já existe Instituto com estes dados');
            }

            $instituteUpdate = Institute::find($params['InstituteId']);
            $instituteUpdate->Name = $params['Name'];
            $instituteUpdate->save();

            return $data->setData($instituteUpdate)->setMessage('Instituto atualizado com sucesso');
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){

            if (!Institute::destroy($id)){
                throw new NotAllowedDeletionException('Não foi possível apagar o instituto, verifique se ele já possui alguma dependencia, como um setor.');
            }
            return new ServiceData(true); 
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            return new ServiceData(Institute::all());
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'InstituteId',
            'Name'
        ];

        return Institute::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'InstituteId' => 'InstituteId',
                'Name' => 'Name'
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

    public function autoComplete()
    {
        return $this->holdMistake(function() {

            $fields = [
                'InstituteId', 
                'Name'
            ];

            $items = Institute::select($fields)->get();
            $result = [];

            foreach($items as $item)
            {
                $result[] = [
                    'id' => $item['InstituteId'],
                    'name' => $item['Name'],
                ];
            }
            return new ServiceData($result);
        });
    }

    public function sectorsAutoComplete($instituteId)
    {
        return new ServiceData($this->sectorService->getByInstitute($instituteId));
    }

}
