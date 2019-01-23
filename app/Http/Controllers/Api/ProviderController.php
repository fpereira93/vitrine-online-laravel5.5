<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\ProviderService;
use Illuminate\Http\Request;

class ProviderController extends BaseController
{
    private $service;

    public function __construct(ProviderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->all();
        return $this->formatResponse($data);
    }

    public function get($id)
    {
        $data = $this->service->get($id);
        return $this->formatResponse($data);
    }

    public function create(Request $request)
    {
        $dataService = $this->service->create($request->all());
        return $this->formatResponse($dataService);
    }

    public function update(Request $request, $providerId)
    {
        $params = array_merge($request->all(), [ 'ProviderId' => $providerId ]);

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
