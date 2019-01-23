<?php

namespace App\Services\Api;

use App\Exceptions\Api\Exception;
use App\Services\BaseService;
use App\Services\Library\ServiceData;

use App\Models\HistoryTicket;

class HistoryTicketService extends BaseService
{

    public function create($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'UserAction' => 'required|exists:users,id',
                'StatusId' => 'required|exists:StatusTicket',
                'Description' => 'required',
                'TicketId' => 'required|exists:Tickets',
            ], true);

            $history = HistoryTicket::create($params);

            return $data->setData($history)->setMessage('Histórico criado com sucesso');
        });
    }
}
