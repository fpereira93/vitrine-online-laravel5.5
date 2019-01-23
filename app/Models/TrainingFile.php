<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingFile extends Model
{
    public $table = "TrainingFiles";
    public $primaryKey = "TrainingFileId";
    
    public function training()
    {
        return $this->belongsTo('App\Models\Training', 'TrainingId');
    }
}
