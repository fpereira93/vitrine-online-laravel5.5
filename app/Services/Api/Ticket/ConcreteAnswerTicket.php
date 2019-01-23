<?php

namespace App\Services\Api\Ticket;

use App\Models\Ticket;
use App\Services\Constants\TicketStatus;
use App\Exceptions\Api\Exception;

class ConcreteAnswerTicket extends TicketStrategy
{

    public function canExecute($ticketId, $userId)
    {
        return $this->hasPermission($ticketId, $userId, function($ticketDb, $userDb){

            $isSuperadmin = $userDb->hasRole('superadmin');
            $isUserAssumed = $ticketDb->AssumedBy == $userDb->id;
            $isUserOpend = $ticketDb->OpendBy == $userDb->id;

            return $ticketDb->CurrentStatus == TicketStatus::EM_TRATAMENTO && (($isSuperadmin && $isUserAssumed) || $isUserOpend);
        });
    }

    public function execute($params)
    {
        return $this->transaction(function() use ($params){

            $data = $this->validate($params, [
                'TicketId' => 'required|exists:Tickets',
                'UserAnswer' => 'required|exists:users,id',
                'Message' => 'required',
            ], true);

            if (!$this->canExecute($params['TicketId'], $params['UserAnswer'])){
                throw new Exception('Ticket não pode ser respondido');
            }

            $responseHistory = $this->historyTicketService->create([
                'UserAction' => $params['UserAnswer'],
                'StatusId' => TicketStatus::EM_TRATAMENTO,
                'Description' => $params['Message'],
                'TicketId' => $params['TicketId']
            ]);

            if (!$responseHistory->validated()){
                throw new Exception('Erro ao criar Histórico do Ticket');
            }

            return $data->setData($responseHistory)->setMessage('Ticket respondido com sucesso');
        });
    }
}
