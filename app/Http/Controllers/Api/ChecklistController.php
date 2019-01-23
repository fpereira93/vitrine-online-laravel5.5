<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\ChecklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends BaseController
{
    private $service;

    public function __construct(ChecklistService $service)
    {
        $this->service = $service;
    }

    public function index(){   }
    
    public function create(Request $request)
    {
        $mergedArray = array_merge($request->all(), ['userId' => Auth::user()->id]);

        $data = $this->service->create($mergedArray);
        return $this->formatResponse($data);
    }

    public function details(int $id)
    {
        $data = $this->service->get($id);
        return $this->formatResponse($data);
    }

    public function update(Request $request, $instituteId)
    {
        $mergedArray = array_merge($request->all(), ['userId' => Auth::user()->id]);

        $data = $this->service->update($mergedArray);
        return $this->formatResponse($data);
    }

    public function delete($id)
    {
        $data = $this->service->delete($id, Auth::user()->id);
        return $this->formatResponse($data);
    }

    public function paginate(Request $request)
    {
        $dataService = $this->service->paginate($request->all());
        return $this->formatResponse($dataService);
    }
}
