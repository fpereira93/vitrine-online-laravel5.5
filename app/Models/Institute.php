<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends BaseModel
{
    use SoftDeletes;
    public $table = 'Institutes';
    public $primaryKey = "InstituteId";

    protected $fillable = [
        'Name'
    ];

    public function sectors()
    {
        return  $this->hasMany('App\Models\Sector', 'InstituteId');
    }
}
