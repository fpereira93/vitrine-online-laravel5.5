<?php

namespace App\Services\Api\Ticket;

use App\Models\Ticket;
use App\Services\Constants\TicketStatus;
use App\Exceptions\Api\Exception;

class ConcreteCreateTicket extends TicketStrategy
{

    public function canExecute($ticketId, $userId)
    {
        return true;
    }

    public function execute($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'TicketSubjectId' => 'required|exists:TicketSubjects',
                'Message' => 'required',
                'OpendBy' => 'required|exists:users,id',
            ], true);

            $ticket = Ticket::create([
                'TicketSubjectId' => $params['TicketSubjectId'],
                'OpendBy' => $params['OpendBy'],
                'CurrentStatus' => TicketStatus::AGUARDANDO_TRATAMENTO,
            ]);

            $responseHistory = $this->historyTicketService->create([
                'UserAction' => $params['OpendBy'],
                'StatusId' => TicketStatus::AGUARDANDO_TRATAMENTO,
                'Description' => $params['Message'],
                'TicketId' => $ticket->TicketId
            ]);

            if (!$responseHistory->validated()){
                throw new Exception('Erro ao criar Histórico do Ticket');
            }

            return $data->setData($ticket)->setMessage('Ticket criado com sucesso');
        });
    }
}
