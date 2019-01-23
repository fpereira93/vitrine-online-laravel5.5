<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\SectorService;
use Illuminate\Http\Request;

class SectorController extends BaseController
{
    private $service;

    public function __construct(SectorService $service)
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
    public function destroy($id)
    {
        $isDeleted = $this->service->destroy($id);
        return $this->formatResponse($isDeleted);
    }
}
