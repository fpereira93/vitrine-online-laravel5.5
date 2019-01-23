<?php

namespace App\Services\Api;

use DB;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Models\Category;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class CategoryService extends BaseService
{
    private function alreadyExists($params)
    {
        return Category::where('name', $params['name'])->where('idCategory', '<>', $params['idCategory'])->count() > 0;
    }

    public function createOrUpdate($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'idCategory' => 'required|integer',
                'name' => 'required',
                'description' => 'required'
            ], true);

            if ($this->alreadyExists($params)){
                throw new DuplicateDataException('Já existe Categoria cadastrada com este nome');
            }

            $category = Category::updateOrCreate(['idCategory' => $params['idCategory']], $params);

            return $data->setData($category)->setMessage("Categoria criada / atualizada com sucesso");
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){

            if (!Category::destroy($id)){
                throw new NotAllowedDeletionException('Não foi possível apagar a Categoria.');
            }
            return new ServiceData(true);
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'idCategory',
            'name',
            'description'
        ];

        return Category::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'idCategory' => 'idCategory',
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
            $categories = Category::where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')->get();

            return (new ServiceData($categories))->setMessage('Categorias recuperadas com sucesso');
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            $categories = Category::all();

            return (new ServiceData($categories))->setMessage('Categorias recuperadas com sucesso');
        });
    }
}
