<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends BaseModel
{
    public $table = "Trainings";
    public $primaryKey = "TrainingId";
    protected $fillable = [
        'TrainerId',
        'Place',
        'Theme',
        'BeginDate',
        'EndDate',
        'Status'
    ];
    
    public function trainer()
    {
        return $this->belongsTo('App\User', 'TrainerId', 'id');
    }

    public function topics()
    {
        return $this->hasMany('App\Models\TrainingTopic', 'TrainingId');
    }

    public function files()
    {
        return $this->hasMany('App\Models\File', 'TrainingFileId');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'UserTrainings', 'TrainingId', 'id');
    }
}
