<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidueDerivation extends BaseModel
{
    public $table = "ResidueDerivations";
    public $primaryKey = "ResidueDerivationId";
    public function residue()
    {
        return $this->belongsTo('App\Models\ResidueType', 'ResidueTypeId');
    }
}