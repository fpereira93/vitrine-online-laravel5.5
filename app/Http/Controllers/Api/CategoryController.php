<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\CategoryService;
use Illuminate\Http\Request;


class CategoryController extends BaseController {
    private $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function paginate(Request $request)
    {
        $response = $this->service->paginate($request->all());
        return $this->formatResponse($response);
    }

    public function create(Request $request)
    {
        $dataService = $this->service->createOrUpdate($request->all());
        return $this->formatResponse($dataService);
    }

    public function update(int $id, Request $request)
    {
        $dataService = $this->service->createOrUpdate($request->all());
        return $this->formatResponse($dataService);
    }

    public function delete(int $id)
    {
        $dataService = $this->service->delete($id);
        return $this->formatResponse($dataService);
    }

    public function autocomplete(Request $request)
    {
        $response = $this->service->autocomplete($request->get('query'));
        return $this->formatResponse($response);
    }

    public function all()
    {
        $response = $this->service->all();
        return $this->formatResponse($response);
    }
}