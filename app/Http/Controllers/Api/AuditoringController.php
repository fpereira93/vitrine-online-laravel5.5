<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\AuditoringService;
use App\Services\Api\ChecklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuditoringController extends BaseController {
    private $service;
    private $checklistService;

    public function __construct(AuditoringService $service, ChecklistService $checklistService)
    {
        $this->service = $service;
        $this->checklistService = $checklistService;
    }

    public function audit() {

    }

    public function all() {
       $loggedUserId = Auth::user()->id;
       $response = $this->service->getFromAuditor($loggedUserId);
       return $this->formatResponse($response);
    }

    public function paginate(Request $request) {
        $loggedUserId = Auth::user()->id;
        $response = $this->service->paginate($request->all(), $loggedUserId);
        return $this->formatResponse($response);
    }

    public function details($checklistId) {
        $loggedUserId = Auth::user()->id;
        $response = $this->checklistService->get($checklistId);
        return $this->formatResponse($response);
    }
}

?>