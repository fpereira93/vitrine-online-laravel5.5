<?php

use Illuminate\Http\Request;
use App\Http\Responses\BaseResponse;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

function allRoutesWebApi()
{
    $routes = [];
    foreach (Route::getRoutes()->getIterator() as $route){
        if ($route->getName()){
            $routes[] = [
                'name' => $route->getName(),
                'url' => url($route->uri()),
                'verb' => $route->methods()[0],
            ];
        }
    }
    return $routes;
}

Route::get('/routes', function(){
    return BaseResponse::successData(allRoutesWebApi());
});

//. ADMIN .//
Route::group([
    'namespace' => 'Admin',
    'as' => 'admin.',
    'prefix' => 'admin'
], function(){

    Route::get('/', 'AdminController@home')->middleware('redirectIfNotAuth')->name('base.index');

    Route::get('/login', 'AdminController@index')->middleware('redirectIfAuth')->name('login.index');

    Route::post('/login', 'AdminController@login')->name('login.user');

    Route::get('/logout', 'AdminController@logout')->name('logout.user');
});

//. SHOWCASE .//
Route::group([
    'namespace' => 'Showcase',
    'as' => 'showcase.',
    'prefix' => 'vitrine'
], function(){

    Route::get('/', 'ShowcaseController@home')->name('home.index');

});

// . REDIRECT .//
Route::get('/', function(){
    return redirect()->route('showcase.home.index');
});

