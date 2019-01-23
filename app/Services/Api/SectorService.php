<?php

namespace App\Services\Api;

use App\Models\Sector;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;

class SectorService extends BaseService
{
    public function all()
    {
        return $this->holdMistake(function(){
            return new ServiceData(Sector::all());
        });
    }
    public function create($params)
    {
        return $this->transaction(function() use ($params){
            $data = $this->validate($params, [
                'Name' => 'required',
            ], true);

            $sec = new Sector();
            $sec->Name = $params['Name'];
            $sec->InstituteId = $params['InstituteId'];
            $sec->save();

            return $data->setData([
                'sector' => $sec
            ])->setMessage('Setor adicionado com sucesso');
        });
    }
    public function destroy($id)
    {
        return $this->holdMistake(function() use($id) {
            $sector = Sector::find($id);
            if (!$sector) throw new RecordNotFoundException('Setor não encontrado');
            $sector->delete();
            return (new ServiceData($sector))->setMessage('Setor apagado com sucesso');
        });
    }
    public function getByInstitute($instituteId)
    {
        return $this->holdMistake(function() use($instituteId) {
            $fields = [
                'SectorId',
                'Name'
            ];
            $items = Sector::select($fields)
                    ->where('InstituteId', '=', $instituteId)
                    ->get();
            $result = [];
            foreach ($items as $item)
            {
                $result[] = [
                    'id' => $item['SectorId'],
                    'name' => $item['Name']
                ];
            }
            return $result;
        });
    }
}
