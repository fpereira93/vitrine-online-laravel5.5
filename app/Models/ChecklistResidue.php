<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistResidue extends BaseModel
{
    public $table = "checklistresidues";
    public $primaryKey = "ChecklistResidueId";
    protected $fillable = [
        'ChecklistId',
        'ResidueTypeId',
        'Segregation',
        'Identification',
        'Treatment',
        'Transport', // creatorId
        'Law',
        'Description',
        'QuantityFound',
        'QuantityAudited',
        'QuantityType',
        'AuditCanceled'
    ];

    public function Goals() {
        return $this->hasMany('App\Models\ChecklistGoal', 'ChecklistResidueId','ChecklistResidueId');
    }

    public function ResidueType() {
        return $this->belongsTo('App\Models\ResidueType', 'ResidueTypeId', 'ResidueTypeId');
    }
}
