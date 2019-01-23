<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\TrainingService;
use Illuminate\Http\Request;

class TrainingController extends BaseController
{
    private $service;

    public function __construct(TrainingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->all();
        return $this->formatResponse($data);
    }
    
    public function get($trainingId)
    {
        $data = $this->service->get($trainingId);
        return $this->formatResponse($data);
    }

    public function paginate(Request $request)
    {
        $dataService = $this->service->paginate($request->all());
        return $this->formatResponse($dataService);
    }

    public function create(Request $request)
    {
        $dataService = $this->service->create($request->all());
        return $this->formatResponse($dataService);
    }

    public function syncTopics(Request $request, $id) 
    {
        $dataService = $this->service->syncTopics($request->all(), $id);
        return $this->formatResponse($dataService);
    }

    public function update(Request $request, $trainingId)
    {
        $params = array_merge($request->all(), [ 'TrainingId' => $trainingId ]);
        $dataService = $this->service->update($params);
        return $this->formatResponse($dataService);
    }

    public function destroy($id)
    {
        $dataService = $this->service->delete($id);
        return $this->formatResponse($dataService);
    }

    public function addUser(Request $request, $id)
    {
        $dataService = $this->service->addUser($request->all(), $id);
        return $this->formatResponse($dataService);
    }
    
    public function removeUser(Request $request, $id)
    {
        $dataService = $this->service->removeUser($request->all(), $id);
        return $this->formatResponse($dataService);
    }
    
    public function summoneds($id)
    {
        $dataService = $this->service->summoneds($id);
        return $this->formatResponse($dataService);
    }
}
