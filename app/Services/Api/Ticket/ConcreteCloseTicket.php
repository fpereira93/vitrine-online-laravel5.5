<?php

namespace App\Services\Api\Ticket;

use App\Models\Ticket;
use App\Services\Constants\TicketStatus;
use App\Exceptions\Api\Exception;

class ConcreteCloseTicket extends TicketStrategy
{

    public function canExecute($ticketId, $userId)
    {
        return $this->hasPermission($ticketId, $userId, function($ticketDb, $userDb){

            $isSuperadmin = $userDb->hasRole('superadmin');
            $isUserAssumed = $ticketDb->AssumedBy == $userDb->id;
            $isUserOpend = $ticketDb->OpendBy == $userDb->id;

            return $ticketDb->CurrentStatus != TicketStatus::FECHADO && (($isSuperadmin && $isUserAssumed) || $isUserOpend);
        });
    }

    public function execute($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'TicketId' => 'required|exists:Tickets',
                'ClosedBy' => 'required|exists:users,id'
            ], true);

            if (!$this->canExecute($params['TicketId'], $params['ClosedBy'])){
                throw new Exception('Ticket não pode ser fechado');
            }

            Ticket::find($params['TicketId'])->update([
                'ClosedBy' => $params['ClosedBy'],
                'CurrentStatus' => TicketStatus::FECHADO,
            ]);

            $responseHistory = $this->historyTicketService->create([
                'UserAction' => $params['ClosedBy'],
                'StatusId' => TicketStatus::FECHADO,
                'Description' => 'Ticket Fechado',
                'TicketId' => $params['TicketId']
            ]);

            if (!$responseHistory->validated()){
                throw new Exception('Erro ao criar Histórico do Ticket');
            }

            return $data->setData($responseHistory)->setMessage('Ticket fechado com sucesso');
        });
    }
}
