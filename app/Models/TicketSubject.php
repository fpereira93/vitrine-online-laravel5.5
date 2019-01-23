<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSubject extends Model
{
    public $table = "TicketSubjects";
    public $primaryKey = "TicketSubjectId";

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket', 'TicketSubjectId');
    }
}
