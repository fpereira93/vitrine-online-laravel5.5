<?php

namespace App\Services\Api;

use App\Services\BaseService;
use App\Models\ChecklistGoal;

class ChecklistResiduesGoalsService extends BaseService
{

    private $residuesActionService;

    public function __construct(ChecklistResiduesGoalsActionsService $residuesActionService)
    {
        $this->residuesActionService = $residuesActionService;
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
            'ChecklistResidueGoalId' => isset($params['id']) ? $params['id'] : null,
            'Goal' => $params['goal'],
            'Objective' => $params['objective'],
            'ChecklistResidueId' => $params['checklistResidueId'],
            'IsAudited' => false,
            'ResidueDerivationId' => $params['derivation']
        ];
    }

    public function createOrUpdate(array $params)
    {
        return $this->transaction(function() use ($params) {

            $data = $this->validate($params, [
                'goal' => 'required',
                'objective' => 'required',
                'checklistResidueId' => 'required',
            ], true);

            $goal = ChecklistGoal::firstOrCreate($this->paramsFormat($params));
            $data->setData($goal);

            $ignoreDelete = [];

            if (isset($params['actions'])){
                foreach ($params['actions'] as $action) {
                    $dataResidue = $this->residuesActionService->createOrUpdate($action + ['checklistResidueGoalId' => $goal->ChecklistResidueGoalId]);
                    $ignoreDelete[] = $dataResidue->getData()->ChecklistResidueGoalActionId;
                }
            }

            $this->residuesActionService->deleteByGoal($goal->ChecklistResidueGoalId, $ignoreDelete);

            return $data;
        });
    }

    public function deleteByResidue(int $residueId, array $ignores = [])
    {
        $query = ChecklistGoal::whereNotIn('ChecklistResidueGoalId', $ignores)
            ->where('ChecklistResidueId', '=', $residueId);

        foreach ($query->get() as $goal) {
            $this->residuesActionService->deleteByGoal($goal->ChecklistResidueGoalId);
            $goal->delete();
        }
    }
}
