<?php
namespace App\Http\Controllers\Showcase;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ShowcaseController extends BaseController
{

    public function home()
    {
        return view('showcase.home');
    }
}
