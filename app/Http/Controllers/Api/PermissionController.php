<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends BaseController
{
    private $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function syncPermissions(Request $request)
    {
        $dataService = $this->service->syncPermissions($request->all());
        return $this->formatResponse($dataService);
    }

    public function syncPermissionsUser(Request $request)
    {
        $dataService = $this->service->syncPermissionsUser($request->all());
        return $this->formatResponse($dataService);
    }

    public function index()
    {
        $data = $this->service->all();
        return $this->formatResponse($data);
    }

    public function rolesUser()
    {
        $userId = Auth::user()->id;
        $dataService = $this->service->rolesUser($userId);
        return $this->formatResponse($dataService);
    }
}
