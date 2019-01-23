<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Traits\FileManipulation;

class ResidueType extends BaseModel
{

    use FileManipulation;

    protected $files = [
        'folderName' => 'residuetypefiles'
    ];

    public $table = "ResidueTypes";
    public $primaryKey = "ResidueTypeId";
    protected $fillable = ['Name','LawObservations'];

    public function links() 
    {
        return $this->hasMany('App\Models\ResidueTypeDocument', 'ResidueTypeId');
    }

    public function derivations()
    {
        return $this->hasMany('App\Models\ResidueDerivations', 'ResidueTypeId');
    }
}
