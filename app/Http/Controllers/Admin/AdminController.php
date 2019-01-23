<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Api\UserService;
use Illuminate\Http\Request;
use Auth;
use Session;

class AdminController extends BaseController
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('login.index');
    }

    public function home()
    {
        return view('base.index');
    }

    public function login(Request $request)
    {
        $dataService = $this->service->login($request->all());

        if ($dataService->validated()){
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);
        }

        return $this->formatResponse($dataService);
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('admin.login.index'));
    }
}
