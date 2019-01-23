<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\InstituteService;
use Illuminate\Http\Request;

class InstituteController extends BaseController
{
    private $service;

    public function __construct(InstituteService $service)
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
        $params = array_merge($request->all(), [ 'InstituteId' => $instituteId ]);

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

    public function autoComplete()
    {
        $dataService = $this->service->autoComplete();
        return $this->formatResponse($dataService);
    }

    public function sectorAutoComplete($instituteId)
    {
        $dataService = $this->service->sectorsAutoComplete($instituteId);
        return $this->formatResponse($dataService);
    }
}
