<?php

namespace App\Services\Api;

use App\Services\BaseService;
use App\Models\ChecklistResidue;

class ChecklistResiduesService extends BaseService
{

    private $residuesGoalsService;

    public function __construct(ChecklistResiduesGoalsService $residuesGoalsService)
    {
        $this->residuesGoalsService = $residuesGoalsService;
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
            'ChecklistResidueId' => isset($params['id']) ? $params['id'] : null,
            'ChecklistId' => $params['checklistId'],
            'ResidueTypeId' => $params['type'],
            'Segregation' => $params['segregation'],
            'Identification' => $params['identificationAndStorage'],
            'Treatment' => $params['treatment'],
            'Transport' => $params['transport'],
            'Law' => $params['law'],
            'Description' => $params['lawDescription'],
            'QuantityFound' => $params['quantity'],
            'QuantityAudited' => 0,

            'AuditCanceled' => 0 // ver com o bruno depois
        ];
    }

    public function createOrUpdate(array $params)
    {
        return $this->transaction(function() use ($params) {

            $data = $this->validate($params, [
                'checklistId' => 'required',
                'type' => 'required',
                'segregation' => 'required',
                'quantity' => 'required',
                'lawDescription' => 'required',
                'identificationAndStorage' => 'required',
                'treatment' => 'required',
                'transport' => 'required',
                'law' => 'required',
                'goals' => 'array',
            ], true);

            $checklistResidue = ChecklistResidue::firstOrCreate($this->paramsFormat($params));
            $data->setData($checklistResidue);

            $ignoreDelete = [];

            if (isset($params['goals'])){
                foreach ($params['goals'] as $goal) {
                    $dataGoal = $this->residuesGoalsService->createOrUpdate($goal + ['checklistResidueId' => $checklistResidue->ChecklistResidueId]);
                    $ignoreDelete[] = $dataGoal->getData()->ChecklistResidueGoalId;
                }
            }

            $this->residuesGoalsService->deleteByResidue($checklistResidue->ChecklistResidueId, $ignoreDelete);

            return $data;
        });
    }

    public function deleteByCheckList(int $checkListId, array $ignores = [])
    {
        $query = ChecklistResidue::whereNotIn('ChecklistResidueId', $ignores)
            ->where('ChecklistId', '=', $checkListId);

        foreach ($query->get() as $residue) {
            $this->residuesGoalsService->deleteByResidue($residue->ChecklistResidueId);
            $residue->delete();
        }
    }
}
