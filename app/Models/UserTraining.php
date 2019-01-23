<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTraining extends BaseModel
{
    public $table = "UserTrainings";
    public $primaryKey = "UserTrainingId";
    protected $fillable = [
        'id',
        'TrainingId'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }

    public function training()
    {
        return $this->hasMany('App\Models\Training', 'TrainingId');
    }
}
