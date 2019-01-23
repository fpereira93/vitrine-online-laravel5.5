<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\ResidueTypeService;
use Illuminate\Http\Request;

class ResidueTypeController extends BaseController
{
    private $service;

    public function __construct(ResidueTypeService $service)
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

    public function update(Request $request, $residueTypeId)
    {
        $params = array_merge($request->all(), [ 'ResidueTypeId' => $residueTypeId ]);

        $dataService = $this->service->update($params);
        return $this->formatResponse($dataService);
    }

    public function destroy($id)
    {
        $dataService = $this->service->delete($id);
        return $this->formatResponse($dataService);
    }

    public function paginate(Request $request)
    {
        $dataService = $this->service->paginate($request->all());
        return $this->formatResponse($dataService);
    }

    public function documents($id)
    {
        $dataService = $this->service->documents($id);
        return $this->formatResponse($dataService);
    }

    public function storeDataDocuments(Request $request)
    {
        $dataService = $this->service->storeDataDocuments($request->all());
        return $this->formatResponse($dataService);
    }

    public function autoComplete(Request $request)
    {
        $dataService = $this->service->autoComplete($request->all());
        return $this->formatResponse($dataService);
    }

    public function derivations($id)
    {
        $dataService = $this->service->derivations($id);
        return $this->formatResponse($dataService);
    }
}
