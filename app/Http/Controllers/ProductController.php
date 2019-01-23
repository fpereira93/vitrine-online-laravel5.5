<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\ProductService;
use Illuminate\Http\Request;


class ProductController extends BaseController {
    private $service;

    public function __construct(ProductService $service)
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

    public function detail(int $id)
    {
        $dataService = $this->service->detail($id);
        return $this->formatResponse($dataService);
    }

    public function containers()
    {
        $dataService = $this->service->containers();
        return $this->formatResponse($dataService);
    }

    public function searchProducts(Request $request)
    {
        $response = $this->service->searchProducts($request->all());
        return $this->formatResponse($response);
    }

    public function likeHeartProduct(Request $request)
    {
        $response = $this->service->likeHeartProduct($request->productId, $request->isLike);
        return $this->formatResponse($response);
    }
}