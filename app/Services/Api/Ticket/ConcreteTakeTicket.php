<?php

namespace App\Services\Api\Ticket;

use App\Models\Ticket;
use App\Services\Constants\TicketStatus;
use App\Exceptions\Api\Exception;

class ConcreteTakeTicket extends TicketStrategy
{

    public function canExecute($ticketId, $userId)
    {
        return $this->hasPermission($ticketId, $userId, function($ticketDb, $userDb){

            $isSuperadmin = $userDb->hasRole('superadmin');

            return $ticketDb->CurrentStatus == TicketStatus::AGUARDANDO_TRATAMENTO && ($isSuperadmin && $ticketDb->OpendBy != $userDb->id);
        });
    }

    public function execute($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'TicketId' => 'required|exists:Tickets',
                'AssumedBy' => 'required|exists:users,id'
            ], true);

            if (!$this->canExecute($params['TicketId'], $params['AssumedBy'])){
                throw new Exception('Ticket não pode ser assumido');
            }

            Ticket::find($params['TicketId'])->update([
                'AssumedBy' => $params['AssumedBy'],
                'CurrentStatus' => TicketStatus::EM_TRATAMENTO,
            ]);

            $responseHistory = $this->historyTicketService->create([
                'UserAction' => $params['AssumedBy'],
                'StatusId' => TicketStatus::EM_TRATAMENTO,
                'Description' => 'Ticket Assumido',
                'TicketId' => $params['TicketId']
            ]);

            if (!$responseHistory->validated()){
                throw new Exception('Erro ao criar Histórico do Ticket');
            }

            return $data->setData($responseHistory)->setMessage('Ticket assumido com sucesso');
        });
    }
}
