<?php

namespace App\Services\Api\Ticket;

use App\Models\Ticket;
use App\Services\BaseService;
use App\Services\Api\HistoryTicketService;
use App\Services\Api\UserService;

abstract class TicketStrategy extends BaseService
{

    protected $historyTicketService;
    protected $userService;

    public function __construct()
    {
        $this->historyTicketService = app(HistoryTicketService::class);
        $this->userService = app(UserService::class);
    }


    protected function hasPermission($ticketId, $userId, $callback)
    {
        $ticket = Ticket::find($ticketId);
        $user = $this->userService->details($userId)->getData();

        if (is_callable($callback)){
            return $callback($ticket, $user);
        }

        return false;
    }

    protected abstract function canExecute($ticketId, $userId);

    protected abstract function execute($params);
}
