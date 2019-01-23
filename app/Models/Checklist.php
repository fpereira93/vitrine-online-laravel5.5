<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends BaseModel
{
    public $table = "Checklists";
    public $primaryKey = "ChecklistId";
    protected $fillable = [
        'SectorId',
        'Multiplier',
        'Auditor',
        'Contact',
        'Date',
        'id', // creatorId
        'IsAudited'
    ];

    public function Residues()
    {
        return $this->hasMany('App\Models\ChecklistResidue', 'ChecklistId', 'ChecklistId');
    }
}
