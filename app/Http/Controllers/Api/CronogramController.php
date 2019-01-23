<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\CronogramService;
use Illuminate\Http\Request;

class CronogramController extends BaseController
{
    private $service;

    public function __construct(CronogramService $service)
    {
        $this->service = $service;
    }

    public function index($year = null, $month = null)
    {
        if ($year && $month) {
            $data = $this->service->allFromMonth($year, $month);
        } else if ($year) {
            $data = $this->service->allFromYear($year);
        } else {
            $data = $this->service->all();
        }

        return $this->formatResponse($data);
    }
    public function nextEvents() {
        return $this->formatResponse($this->service->nextEvents());
    }

    public function create(Request $request)
    {
        $dataService = $this->service->create($request->all());
        return $this->formatResponse($dataService);
    }

    public function update(Request $request, $instituteId)
    {
        $params = array_merge($request->all(), [ 'EventId' => $instituteId ]);

        $dataService = $this->service->update($params);
        return $this->formatResponse($dataService);
    }

    public function destroy($id)
    {
        $dataService = $this->service->delete($id);
        return $this->formatResponse($dataService);
    }
}
