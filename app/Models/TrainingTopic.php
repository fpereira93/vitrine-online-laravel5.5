<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTopic extends BaseModel
{
    public $table = "TrainingTopics";
    public $primaryKey = "TrainingTopicId";
    protected $fillable = [
        'Description',
        'TrainingId'
    ];
}
