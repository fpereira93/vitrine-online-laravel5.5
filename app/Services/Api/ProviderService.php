<?php

namespace App\Services\Api;

use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Models\Provider;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class ProviderService extends BaseService
{

    private function storeDocuments($documents, $providerId)
    {
        $residueType = Provider::find($providerId);

        foreach ($documents as $document) {
            if (((int)$document['FileId']) == 0){
                $residueType->saveFile($document);
            } elseif ($document['deleted'] == 1){
                $residueType->deleteFile($document['FileId']);
            } else {
                $residueType->storeFileDb($document);
            }
        }
    }

    private function toArrayResiduesNoDeleted($acceptedResidues)
    {
        $custom = [];

        foreach ($acceptedResidues as $residueType) {
            if ($residueType['deleted'] == 0){
                $custom[] = $residueType['ResidueTypeId'];
            }
        }

        return $custom;
    }

    private function storeDocumentsAndResiduesType($params, $providerDb)
    {
        if (isset($params['Docs'])){
            $this->storeDocuments($params['Docs'], $providerDb->ProviderId);
        }

        if (isset($params['AcceptedResidues'])){
            $providerDb->residues()->sync( $this->toArrayResiduesNoDeleted($params['AcceptedResidues']) );
        }
    }

    public function create($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'SocialName' => 'required',
                'CNPJ' => 'required',
            ], true);

            $provider = new Provider;
            $provider->fill($params);
            $provider->save();

            $this->storeDocumentsAndResiduesType($params, $provider);

            return $data->setData($provider)->setMessage('Fornecedor criado com sucesso');
        });
    }

    public function update($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'ProviderId' => 'required|exists:Providers',
                'SocialName' => 'required',
            ], true);

            $provider = Provider::find($params['ProviderId']);
            $provider->fill($params);
            $provider->save();

            $this->storeDocumentsAndResiduesType($params, $provider);

            return $data->setData($provider)->setMessage('Instituto atualizado com sucesso');
        });
    }

    public function delete(int $id)
    {
        return $this->transaction(function() use ($id){

            if (!Provider::destroy($id)){
                throw new NotAllowedDeletionException('Não foi possível apagar o fornecedor, verifique se ele possui alguma pendencia.');
            }
            return (new ServiceData())->setMessage('Fornecedor apagado com sucesso');
        });
    }

    public function all()
    {
        return $this->holdMistake(function(){
            return new ServiceData(Provider::all());
        });
    }

    private function getQueryForFilter()
    {
        $fields = [
            'ProviderId',
            'SocialName',
            'FantasyName',
            'CNPJ'
        ];

        return Provider::select($fields);
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->getQueryForFilter();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'ProviderId' => 'ProviderId',
                'SocialName' => 'SocialName',
                'FantasyName' => 'FantasyName',
                'CNPJ' => 'CNPJ',
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

    public function get($id)
    {
        return $this->holdMistake(function() use($id) {
            $provider = Provider::find($id);

            if (!$provider) {
                throw new RegisterNotFoundException('Fornecedor não encontrado');
            }

            $response = [
                'provider' => $provider,
                'documents' => $provider->files(),
                'residues' => $provider->residues->toArray()
            ];

            return new ServiceData($response);
        });
    }
}
