<?php

namespace App\Services\Api;

use DB;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Models\Brand;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class BrandService extends BaseService
{
    private function alreadyExists($params)
    {
        return Brand::where('name', $params['name'])->where('idBrand', '<>', $params['idBrand'])->count() > 0;
    }

    public function createOrUpdate($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'idBrand' => 'required|integer',
                'name' => 'required',
                'description' => 'required'
            ], true);

            if ($this->alreadyExists($params)){
                throw new DuplicateDataException('Já existe Marca cadastrada com este nome');
            }

            $brand = Brand::updateOrCreate(['idBrand' => $params['idBrand']], $params);

            return $data->setData($brand)->setMessage("Marca criada / atualizada com sucesso");
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){

            if (!Brand::destroy($id)){
                throw new NotAllowedDeletionException('Não foi possível apagar a Marca.');
            }
            return new ServiceData(true);
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'idBrand',
            'name',
            'description'
        ];

        return Brand::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'idBrand' => 'idBrand',
                'name' => 'name',
                'description' => 'description'
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

    public function autocomplete(string $query)
    {
        return $this->holdMistake(function() use ($query){
            $brands = Brand::where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')->get();

            return (new ServiceData($brands))->setMessage('Marcas recuperadas com sucesso');
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            $brands = Brand::all();

            return (new ServiceData($brands))->setMessage('Marcas recuperadas com sucesso');
        });
    }
}
