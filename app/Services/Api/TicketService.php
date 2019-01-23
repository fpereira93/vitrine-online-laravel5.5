<?php

namespace App\Services\Api;

use App\Services\BaseService;
use App\Services\Library\ServiceData;

use App\Models\TicketSubject;
use App\Models\StatusTicket;
use App\Models\Ticket;

use App\Services\Constants\TicketStatus;

use App\Services\Library\Datatable\CommonDatatable;
use App\Services\Library\Datatable\ResponseCommonDatatable;

use App\Services\Api\Ticket\ConcreteCreateTicket;
use App\Services\Api\Ticket\ConcreteCloseTicket;
use App\Services\Api\Ticket\ConcreteAnswerTicket;
use App\Services\Api\Ticket\ConcreteTakeTicket;

class TicketService extends BaseService
{

    private $historyTicketService;
    private $userService;

    public function __construct(HistoryTicketService $historyService, UserService $userService)
    {
        $this->historyTicketService = $historyService;
        $this->userService = $userService;
    }

    public function subjects()
    {
        return $this->holdMistake(function(){
            return new ServiceData(TicketSubject::all());
        });
    }

    public function status()
    {
        return $this->holdMistake(function(){
            return new ServiceData(StatusTicket::all());
        });
    }

    public function create($params)
    {
        return (new ConcreteCreateTicket)->execute($params);
    }

    public function closeTicket($params)
    {
        return (new ConcreteCloseTicket)->execute($params);
    }

    public function takeTicket($params)
    {
        return (new ConcreteTakeTicket)->execute($params);
    }

    public function answerTicket($params)
    {
        return (new ConcreteAnswerTicket)->execute($params);
    }

    private function getQueryForFilter($filterArray)
    {
        $user = $this->userService->details($filterArray['UserId'])->getData();

        $fields = [
            'OpendBy',
            'TicketId',
            'users.name',
            'TicketSubjects.Description',
        ];

        $query = Ticket::select($fields)
            ->join('users', 'users.id', '=', 'Tickets.OpendBy')
            ->join('TicketSubjects', 'TicketSubjects.TicketSubjectId', '=', 'Tickets.TicketSubjectId');

        if ($user->hasRole('superadmin')){
            $query->where(function($query) use ($filterArray){
                $query->where('Tickets.AssumedBy', $filterArray['UserId'])
                ->orWhereNull('Tickets.AssumedBy')
                ->orwhere('Tickets.OpendBy', $filterArray['UserId']);
            });

        } else {
            $query->where('Tickets.OpendBy', $filterArray['UserId']);
        }

        return $query;
    }

    public function paginate($filterArray)
    {
        return $this->holdMistake(function() use ($filterArray){
            $query = $this->getQueryForFilter($filterArray);

            $dataTableFilter = new CommonDatatable($filterArray, [
                'TicketId' => 'TicketId',
                'name' => 'name',
                'SubjectDescription' => 'Description',
                'TicketSubjectId' => 'Tickets.TicketSubjectId',
                'StatusId' => 'Tickets.CurrentStatus',
            ]);

            $response = new ResponseCommonDatatable();
            $response->fillDefaultDataTable($dataTableFilter, $query);

            return new ServiceData($response);
        });
    }

    public function get($params)
    {
        return $this->holdMistake(function() use ($params){

            $answer = (new ConcreteAnswerTicket);
            $take = (new ConcreteTakeTicket);
            $close = (new ConcreteCloseTicket);

            return new ServiceData([
                'ticket' => Ticket::with('history.userAction')->find($params['TicketId']),
                'permission' => [
                    'answer' => $answer->canExecute($params['TicketId'], $params['UserId']),
                    'take' => $take->canExecute($params['TicketId'], $params['UserId']),
                    'close' => $close->canExecute($params['TicketId'], $params['UserId']),
                ]
            ]);
        });
    }
}
