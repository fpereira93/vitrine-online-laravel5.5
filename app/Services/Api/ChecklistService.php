<?php

namespace App\Services\Api;

use Auth;
use DB;
use App\User;
use Carbon\Carbon;
use App\Models\Checklist;
use App\Models\ChecklistResidue;
use App\Models\ChecklistGoal;
use App\Models\ChecklistAction;
use App\Models\Sector;
use App\Services\BaseService;
use App\Services\Library\ServiceData;
use App\Exceptions\Api\DuplicateDataException;
use App\Exceptions\Api\RegisterNotFoundException;
use App\Exceptions\Api\NotAllowedDeletionException;
use App\Exceptions\Api\ValidatingException;
use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

class ChecklistService extends BaseService
{
    private $checklistResiduesService;

    public function __construct(ChecklistResiduesService $checklistResiduesService)
    {
        $this->checklistResiduesService = $checklistResiduesService;
    }

    public function create($params)
    {
        return $this->createOrUpdate($params);
    }

    public function update($params)
    {
        return $this->createOrUpdate($params);
    }

    private function paramsFormat(array $params): array
    {
        return [
            'ChecklistId' => isset($params['checklistId']) ? $params['checklistId'] : null,
            'SectorId' => $params['sectorId'],
            'Auditor' => $params['auditorId'],
            'Contact' => $params['contactId'],
            'Date' => $params['date'],
            'id' => $params['userId'],
            'Multiplier' => $params['multiplierId']
        ];
    }

    public function createOrUpdate(array $params)
    {
        return $this->transaction(function() use ($params) {

            $data = $this->validate($params, [
                'sectorId' => 'required',
                'auditorId' => 'required',
                'contactId' => 'required',
                'date' => 'required',
                'userId' => 'required',
                'multiplierId' => 'required',
                'residues' => 'array',
            ], true);

            $checklist = Checklist::firstOrCreate($this->paramsFormat($params));
            $data->setData($checklist);

            $ignoreDelete = [];

            if (isset($params['residues'])){
                foreach ($params['residues'] as $residue) {
                    $dataCheckList = $this->checklistResiduesService->createOrUpdate($residue + ['checklistId' => $checklist->ChecklistId]);
                    $ignoreDelete[] = $dataCheckList->getData()->ChecklistResidueId;
                }
            }

            $this->checklistResiduesService->deleteByCheckList($checklist->ChecklistId, $ignoreDelete);

            return $data->setMessage('Checklist salvo com sucesso!');
        });
    }

    private function validateDeleteCheckList($dbCheckList, $userId)
    {
        if (!$dbCheckList) {
            throw new RecordNotFoundException('Registro não encontrado');
        }
        
        if ($dbCheckList->isAudited) {
            throw new NotAllowedDeletionException('Não é possível apagar um checklist que já esteja sendo auditado');
        }
        
        if ($dbCheckList->id != $userId) {
            throw new NotAllowedDeletionException('Só é possível apagar um checklist que seja criado por você');
        }
    }

    public function delete(int $id, int $creatorId)
    {
        return $this->transaction(function() use ($id, $creatorId) {

            $checklist = Checklist::find($id);

            $this->validateDeleteCheckList($checklist, $creatorId);

            $this->checklistResiduesService->deleteByCheckList($checklist->ChecklistId);

            if (!$checklist->delete()){
                throw new NotAllowedDeletionException('Não foi possível apagar o checklist.');
            }

            return (new ServiceData($checklist))->setMessage('Checklist deletado com sucesso.');
        });
    }

    public function get($id)
    {
        return $this->holdMistake(function() use ($id) {
            $checklist = Checklist::find($id);
            $checklist->residues;

            $sector = Sector::find($checklist->SectorId);
            $checklist->InstituteId = $sector->InstituteId;
            $checklist->SectorName = $sector->Name;
            $checklist->InstituteName = $sector->institute->Name;
            
            $multiplier = User::find($checklist->Multiplier);
            $auditor = User::find($checklist->Auditor);
            $contact = User::find($checklist->Contact);

            $checklist->MultiplierName = $multiplier->name;
            $checklist->AuditorName = $auditor->name;
            $checklist->ContactName = $contact->name;

            foreach ($checklist->residues as $residue) {
                $residue->ResidueType = $residue->ResidueType->Name;
                $residue->goals;
                foreach($residue->goals as $goal) {
                    $goal->Derivation = $goal->ResidueDerivation->Derivation;
                    $goal->actions;
                }
            }
            return new ServiceData($checklist);
        });
    }

    public function all()
    {
        return $this->holdMistake(function() {
            return new ServiceData(Checklist::where('id', '=', Auth::user()->id)->get());
        });
    }

    public function queryPaginate() 
    {
        $fields = [
            'ChecklistId',
            'sectors.Name as Sector',
            'institutes.Name as Institute',
            'm.name as Multiplier',
            'a.name as Auditor',
            'c.name as Contact',
            'Date as Data'
        ];

        $query = Checklist::select($fields)
            ->join('sectors', 'sectors.sectorid', '=', 'checklists.sectorid')
            ->join('institutes', 'institutes.instituteid', '=', 'sectors.instituteid')
            ->join('users as m', 'm.id', '=', 'multiplier')
            ->join('users as a', 'a.id', '=', 'auditor')
            ->join('users as c', 'c.id', '=', 'contact');
        return $query;

    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){

            $query = $this->queryPaginate();

            $dataTableFilter = new CommonDatatable($filterArray, [
                'ChecklistId' => 'ChecklistId',
                'Sector' => 'Sector',
                'Institute' => 'Institute',
                'Multiplier' => 'Multiplier',
                'Auditor' => 'Auditor',
                'Contact' => 'Contact',
                'Data' => 'Data'
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);
            return new ServiceData($response);
        });
    }
}
