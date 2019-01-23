<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\Api\TicketService;

class TicketController extends BaseController
{
    private $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function subjects()
    {
        return $this->formatResponse($this->service->subjects());
    }

    public function status()
    {
        return $this->formatResponse($this->service->status());
    }

    public function create(Request $request)
    {
        $dataService = $this->service->create(array_merge($request->all(), [
            'OpendBy' => Auth::user()->id
        ]));

        return $this->formatResponse($dataService);
    }

    public function closeTicket(Request $request, $id)
    {
        $dataService = $this->service->closeTicket(array_merge($request->all(), [
            'TicketId' => $id,
            'ClosedBy' => Auth::user()->id
        ]));

        return $this->formatResponse($dataService);
    }

    public function takeTicket(Request $request, $id)
    {
        $dataService = $this->service->takeTicket(array_merge($request->all(), [
            'TicketId' => $id,
            'AssumedBy' => Auth::user()->id
        ]));

        return $this->formatResponse($dataService);
    }

    public function answerTicket(Request $request, $id)
    {
        $dataService = $this->service->answerTicket(array_merge($request->all(), [
            'TicketId' => $id,
            'UserAnswer' => Auth::user()->id
        ]));

        return $this->formatResponse($dataService);
    }

    public function paginate(Request $request)
    {
        $dataService = $this->service->paginate(array_merge($request->all(), [
            'UserId' => Auth::user()->id
        ]));

        return $this->formatResponse($dataService);
    }

    public function get($id)
    {
        $dataService = $this->service->get([
            'TicketId' => $id,
            'UserId' => Auth::user()->id
        ]);

        return $this->formatResponse($dataService);
    }
}
