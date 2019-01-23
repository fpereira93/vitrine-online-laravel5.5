<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistGoal extends BaseModel
{
    public $table = "checklistresiduegoals";
    public $primaryKey = "ChecklistResidueGoalId";
    protected $fillable = [
        'Goal',
        'Objective',
        'ChecklistResidueId',
        'ResidueDerivationId',
        'IsAudited'
    ];

    public function Actions() {
        return $this->hasMany('App\Models\ChecklistAction', 'ChecklistResidueGoalId');
    }

    public function ResidueDerivation() {
        return $this->belongsTo('App\Models\ResidueDerivation', 'ResidueDerivationId');
    }
}
