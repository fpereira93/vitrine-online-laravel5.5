<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends BaseModel
{
    use SoftDeletes;
    public $table = "Sectors";
    public $primaryKey = "SectorId";

    public function institute() 
    {
        return $this->belongsTo('App\Models\Institute', 'InstituteId');
    }

    public function users() 
    {
        return $this->belongsToMany('App\User', 'UserSectors', 'SectorId', 'id');
    }
}
