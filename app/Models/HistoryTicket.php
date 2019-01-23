<?php

namespace App\Models;

class HistoryTicket extends BaseModel
{

    public $table = 'HistoryTicket';
    public $primaryKey = "HistoryId";

    protected $fillable = [
        'UserAction',
        'StatusId',
        'Description',
        'TicketId',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function userAction()
    {
        return $this->hasOne('App\User', 'id', 'UserAction');
    }

    public function status()
    {
        return $this->hasOne('App\Models\StatusTicket', 'StatusId', 'StatusId');
    }

    public function ticket()
    {
        return $this->hasOne('App\Models\Ticket', 'TicketId', 'TicketId');
    }
}
