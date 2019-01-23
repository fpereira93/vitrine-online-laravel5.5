<?php

namespace App\Services\Api;

use App\Services\BaseService;
use App\Models\ChecklistAction;

class ChecklistResiduesGoalsActionsService extends BaseService
{

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
            'ChecklistResidueGoalActionId' => isset($params['id']) ? $params['id'] : null,
            'ChecklistResidueGoalId' => $params['checklistResidueGoalId'],
            'Action' => $params['action'],
            'Place' => $params['place'],
            'Responsible' => $params['responsible'],
            'DeadlineType' => $params['deadlineType'],
            'Deadline' => $params['deadline'],
        ];
    }

    public function createOrUpdate(array $params)
    {
        return $this->transaction(function() use ($params) {

            $data = $this->validate($params, [
                'checklistResidueGoalId' => 'required',
                'action' => 'required',
                'place' => 'required',
                'responsible' => 'required',
                'deadlineType' => 'required',
                'deadline' => 'required',
            ], true);

            $action = ChecklistAction::firstOrCreate($this->paramsFormat($params));

            return $data->setData($action);
        });
    }

    public function deleteByGoal(int $residueGoalsId, array $ignores = [])
    {
        return ChecklistAction::whereNotIn('ChecklistResidueGoalActionId', $ignores)
            ->where('ChecklistResidueGoalId', '=', $residueGoalsId)->delete();
    }
}