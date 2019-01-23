<?php

namespace App\Models;

class StatusTicket extends BaseModel
{

    public $table = 'StatusTicket';
    public $primaryKey = "StatusId";

    protected $fillable = [
        'StatusId',
        'Description'
    ];
}
