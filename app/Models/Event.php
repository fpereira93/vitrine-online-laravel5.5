<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends BaseModel
{
    public $table = "Events";
    public $primaryKey = "EventId";

    protected $fillable = ['EventId', 'id', 'Name','Description', 'BeginDate','EndDate','allDay'];

    public function user() 
    {
        return $this->belongsTo('App\User', 'id');
    }
}
