<?php

namespace App\Services\Api;
use App\Models\ResidueDerivation;
use App\Models\ResidueType;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;

use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

use App\Models\Files;
use App\Models\ResidueTypeDocument;

class ResidueTypeService extends BaseService
{
    public function create($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'Name' => 'required',
            ], true);

            if (ResidueType::where('Name', $params['Name'])->count() > 0) {
                throw new DuplicateDataException('Já existe resíduo com estes dados');
            }

            $residue = ResidueType::firstOrCreate($params);

            return $data->setData($residue)->setMessage('Resíduo criado com sucesso');
        });
    }

    public function update($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'ResidueTypeId' => 'required|exists:ResidueTypes',
                'Name' => 'required',
            ], true);

            $residue = ResidueType::where("ResidueTypeId", "<>", $params['ResidueTypeId'])
            ->where("Name", $params['Name'])->first();

            if ($residue){
                throw new DuplicateDataException('Já existe um resíduo com estes dados');
            }

            $residueUpdate = ResidueType::find($params['ResidueTypeId']);
            $residueUpdate->fill($params);
            $residueUpdate->save();

            return $data->setData($residueUpdate)->setMessage('Residuo atualizado com sucesso');
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){

            if (!ResidueType::destroy($id)){
                throw new NotAllowedDeletionException('Não foi possível apagar o resíduo, verifique se ele já possui alguma dependencias.');
            }
            return (new ServiceData())->setMessage('Tipo de Residuo apagado com sucesso');
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            return new ServiceData(ResidueType::all());
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'ResidueTypeId',
            'Name',
            'LawObservations'
        ];

        return ResidueType::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'ResidueTypeId' => 'ResidueTypeId',
                'Name' => 'Name',
                'LawObservations' => 'LawObservations',
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

    public function documents($residueTypeId)
    {
        return $this->holdMistake(function() use ($residueTypeId){

            $residue = ResidueType::find($residueTypeId);

            $response = [
                'files' => $residue->files(),
                'links' => $residue->links->toArray()
            ];

            return new ServiceData($response);
        });
    }

    private function storeDataFiles($arrayFiles, $residueTypeId)
    {
        $residueType = ResidueType::find($residueTypeId);

        foreach ($arrayFiles as $file) {
            if (!empty($file['FileId'])){
                if ($file['deleted'] == 1){
                    $residueType->deleteFile($file['FileId']);
                } else {
                    $residueType->storeFileDb($file);
                }
            } else {
                $residueType->saveFile($file);
            }
        }
    }

    private function storeDataLinks($arrayLinks, $residueTypeId)
    {
        foreach ($arrayLinks as $link) {
            $link['ResidueTypeId'] = $residueTypeId;

            if (!empty($link['ResidueTypeDocumentsId'])){
                $residueTypeDocument = ResidueTypeDocument::find($link['ResidueTypeDocumentsId']);

                if ($link['deleted'] == 1){
                    $residueTypeDocument->delete();
                } else {
                    $residueTypeDocument->fill($link);
                    $residueTypeDocument->save();
                }
            } else {
                ResidueTypeDocument::create($link);
            }
        }
    }

    public function storeDataDocuments($documents)
    {
        return $this->transaction(function() use ($documents){
            if (isset($documents['files'])){
                $this->storeDataFiles($documents['files'], $documents['ResidueTypeId']);
            }

            if (isset($documents['links'])){
                $this->storeDataLinks($documents['links'], $documents['ResidueTypeId']);
            }

            return $this->documents($documents['ResidueTypeId']);
        });
    }

    public function autoComplete($params)
    {
        return $this->holdMistake(function() use ($params){

            $query = ResidueType::query();

            if (!empty($params['term'])){
                $query->where('Name', 'like', '%' . $params['term'] . '%');
            }

            $residuesResponse = [];

            foreach ($query->get() as $residue) {
                $residuesResponse[] = [
                   'id' => $residue->ResidueTypeId,
                   'name' => $residue->Name
                ];
            }

            return (new ServiceData($residuesResponse))->setMessage('Resíduos recuperados com sucesso');
        });
    }

    public function derivations($id)
    {
        return $this->holdMistake(function() use($id) {
            $query = ResidueDerivation::query();
            $result = $query->where('ResidueTypeId', '=', $id)->get();
            $array = [];
            foreach ($result as $r)
            {
                $array[] = [
                    'id' => $r->ResidueDerivationId,
                    'name' => $r->Derivation,
                ];
            }
            return (new ServiceData($array))->setMessage('Derivações recuperadas com sucesso');
        });
    }
}
