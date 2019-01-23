<?php

namespace App\Services\Api;

use DB;
use Carbon\Carbon;
use App\Models\Checklist;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class AuditoringService extends BaseService
{
    public function queryPaginate($auditorId) 
    {
        $fields = [
            'ChecklistId',
            'sectors.Name as Sector',
            'institutes.Name as Institute',
            'm.name as Multiplier',
            'a.name as Auditor',
            'c.name as Contact',
            'Date as Data',
            'IsAudited'
        ];

        $query = Checklist::select($fields)
            ->join('sectors', 'sectors.sectorid', '=', 'checklists.sectorid')
            ->join('institutes', 'institutes.instituteid', '=', 'sectors.instituteid')
            ->join('users as m', 'm.id', '=', 'multiplier')
            ->join('users as a', 'a.id', '=', 'auditor')
            ->join('users as c', 'c.id', '=', 'contact')
            ->where('a.id', '=', $auditorId);
        return $query;

    }

    public function paginate($filterArray, $auditorId)
    {
        return $this->holdMistake(function() use ($filterArray, $auditorId){

            $query = $this->queryPaginate($auditorId);

            $dataTableFilter = new CommonDatatable($filterArray, [
                'ChecklistId' => 'ChecklistId',
                'Sector' => 'Sector',
                'Institute' => 'Institute',
                'Multiplier' => 'Multiplier',
                'Auditor' => 'Auditor',
                'Contact' => 'Contact',
                'Data' => 'Data',
                'IsAudited' => 'IsAudited'
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);
            $response->data = $this->formatResponseData($response->data);
            return new ServiceData($response);
        });
    }

    private function formatResponseData($responseData) {
        for ($i = 0; $i < count($responseData); $i++) {
            $responseData[$i]['IsAudited'] = $responseData[$i]['IsAudited'] ? 'Sim' : 'Não';
        }
        return $responseData;
    }
}