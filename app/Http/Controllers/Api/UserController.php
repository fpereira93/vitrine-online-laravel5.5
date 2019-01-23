<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Services\Api\UserService;
use App\Services\Library\ServiceData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->formatResponse($this->service->all());
    }

    public function autocomplete()
    {
        return $this->formatResponse($this->service->autocomplete());
    }

    public function store(Request $request)
    {
        $dataService = $this->service->register($request->all());
        return $this->formatResponse($dataService);
    }

    public function destroy($id)
    {
        $dataService = $this->service->destroy($id);
        return $this->formatResponse($dataService);
    }

    public function login(Request $request)
    {
        $dataService = $this->service->login($request->all());
        return $this->formatResponse($dataService);
    }

    public function show()
    {
        $user = Auth::user();
        $user->urlAvatar = $this->service->getUrlAvatarUser($user->id);

        $data = (new ServiceData($user))->setMessage('Usuário recuperado com sucesso');

        return $this->formatResponse($data);
    }

    public function update(Request $request, $id)
    {
        $requestArray = $request->all();
        $requestArray['id'] = $id;

        $dataService = $this->service->update($requestArray);
        return $this->formatResponse($dataService);
    }

    public function paginate(Request $request)
    {
        $dataService = $this->service->paginate($request->all());
        return $this->formatResponse($dataService);
    }
}
