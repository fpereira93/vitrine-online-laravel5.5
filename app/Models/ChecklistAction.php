<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistAction extends BaseModel
{
    public $table = "checklistresiduegoalactions";
    public $primaryKey = "ChecklistResidueGoalActionId";
    protected $fillable = [
        'ChecklistResidueGoalId',
        'Action',
        'Place',
        'Responsible',
        'DeadlineType',
        'Deadline'
    ];
}
