<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends BaseModel
{
    public $table = "Tickets";
    public $primaryKey = "TicketId";
    protected $fillable = ['TicketSubjectId', 'Message', 'OpendBy', 'AssumedBy', 'ClosedBy', 'CurrentStatus'];

    public function ticketSubject()
    {
        return $this->hasOne('App\Models\TicketSubject', 'TicketSubjectId', 'TicketSubjectId');
    }

    public function history()
    {
        return  $this->hasMany('App\Models\HistoryTicket', 'TicketId', 'TicketId');
    }
}
