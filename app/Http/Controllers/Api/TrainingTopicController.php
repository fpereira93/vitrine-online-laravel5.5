<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\TrainingTopicService;
use Illuminate\Http\Request;

class TrainingTopicController extends BaseController
{
    private $service;

    public function __construct(TrainingTopicService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->all();
        return $this->formatResponse($data);
    }

    public function create(Request $request)
    {
        $dataService = $this->service->create($request->all());
        return $this->formatResponse($dataService);
    }

    public function update(Request $request, $instituteId)
    {
        $params = array_merge($request->all(), [ 'TrainingTopicId' => $instituteId ]);

        $dataService = $this->service->update($params);
        return $this->formatResponse($dataService);
    }

    public function paginate(Request $request)
    {
        $dataService = $this->service->paginate($request->all());
        return $this->formatResponse($dataService);
    }

    public function destroy($id)
    {
        $dataService = $this->service->delete($id);
        return $this->formatResponse($dataService);
    }
}