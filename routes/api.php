<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

    //. CATEGORY - Routes //
    Route::group([
        'prefix' => 'category',
        'as' => 'category.',
    ], function () {

        Route::get('/', 'CategoryController@all')->name('all');

        Route::group([
            'middleware' => [ 'auth:api', 'role:superadmin' ]
        ], function() {
            Route::post('/', 'CategoryController@create')->name('create');
            Route::put('/{id}', 'CategoryController@update')->name('update');
            Route::post('/paginate', 'CategoryController@paginate')->name('paginate');
            Route::delete('/{id}', 'CategoryController@delete')->name('delete');
            Route::get('/autocomplete', 'CategoryController@autocomplete')->name('autocomplete');
        });
    });

    //. BRAND - Routes //
    Route::group([
        'prefix' => 'brand',
        'as' => 'brand.',
    ], function () {

        Route::get('/', 'BrandController@all')->name('all');

        Route::group([
            'middleware' => [ 'auth:api', 'role:superadmin' ]
        ], function() {
            Route::post('/', 'BrandController@create')->name('create');
            Route::put('/{id}', 'BrandController@update')->name('update');
            Route::post('/paginate', 'BrandController@paginate')->name('paginate');
            Route::delete('/{id}', 'BrandController@delete')->name('delete');
            Route::get('/autocomplete', 'BrandController@autocomplete')->name('autocomplete');
        });
    });

    //. PRODUCT - Routes //
    Route::group([
        'prefix' => 'product',
        'as' => 'product.',
    ], function () {

        Route::post('/searchProducts', 'ProductController@searchProducts')->name('searchProducts');
        Route::post('/like-heart-product', 'ProductController@likeHeartProduct')->name('likeHeartProduct');

        Route::group([
            'middleware' => [ 'auth:api', 'role:superadmin' ]
        ], function() {
            Route::post('/', 'ProductController@create')->name('create');
            Route::put('/{id}', 'ProductController@update')->name('update');
            Route::post('/paginate', 'ProductController@paginate')->name('paginate');
            Route::delete('/{id}', 'ProductController@delete')->name('delete');
            Route::get('/detail/{id}', 'ProductController@detail')->name('detail');
            Route::get('/containers', 'ProductController@containers')->name('containers');
        });
    });


    //. USER - Routes //
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', 'UserController@index')->middleware('auth:api')->middleware('role:superadmin');
        Route::get('/detail-user', 'UserController@show')->name('detail-user')->middleware('auth:api');
        Route::get('/autocomplete', 'UserController@autocomplete')->name('autocomplete')->middleware('auth:api');
        Route::post('/', 'UserController@store')->name('create')->middleware('auth:api')->middleware('role:superadmin');
        Route::post('/login', 'UserController@login')->name('login');
        Route::post('/paginate', 'UserController@paginate')->name('paginate');
        Route::put('/{id}', 'UserController@update')->name('update')->middleware('auth:api');
        Route::delete('/{id}', 'UserController@destroy')->name('destroy')->middleware('auth:api')->middleware('role:superadmin');
    });

    //. PERMISSION - Routes //
    Route::group([
        'prefix' => 'permission',
        'as' => 'permission.',
        'middleware' => [ 'auth:api', 'role:superadmin' ]
    ], function () {
        Route::get('/', 'PermissionController@index')->name('index');
        Route::post('/sync', 'PermissionController@syncPermissions')->name('sync');
        Route::post('/syncUser', 'PermissionController@syncPermissionsUser')->name('syncUser');
        Route::get('/rolesUser', 'PermissionController@rolesUser')->name('rolesUser');
    });








































    //. INSTITUTES - Routes //
    Route::group(['prefix' => 'institutes','as' => 'institutes.', 'middleware' => ['auth:api']],
    function() {
        Route::get('/', 'InstituteController@index')->name('index');
        Route::post('/', 'InstituteController@create')->name('create');
        Route::put('/{id}', 'InstituteController@update')->name('update');
        Route::delete('/{id}', 'InstituteController@destroy')->name('destroy');

        Route::post('/paginate', 'InstituteController@paginate')->name('paginate');
        Route::get('/autocomplete', 'InstituteController@autoComplete')->name('autocomplete');
        Route::get('/{id}/sectors/autocomplete', 'InstituteController@sectorAutoComplete')->name('sectors.autocomplete');
    });

    //. SECTORS - Routes //
    Route::group(['prefix' => 'sectors','as' => 'sectors.', 'middleware' => ['auth:api']],
    function() {
        Route::get('/', 'SectorController@index')->name('index');
        Route::post('/', 'SectorController@create')->name('create');
        Route::delete('/{id}', 'SectorController@destroy')->name('destroy');
    });

    //. ResidueTypes - Routes //
    Route::group(['prefix' => 'residues','as' => 'residues.', 'middleware' => ['auth:api']],
    function() {
        Route::get('/', 'ResidueTypeController@index')->name('index');
        Route::get('{id}/documents', 'ResidueTypeController@documents')->name('documents');
        Route::get('{id}/derivations', 'ResidueTypeController@derivations')->name('derivations.autocomplete');

        Route::any('/autocomplete', 'ResidueTypeController@autoComplete')->name('autocomplete');

        Route::post('/', 'ResidueTypeController@create')->name('create');
        Route::post('/paginate', 'ResidueTypeController@paginate')->name('paginate');
        Route::post('storeDataDocuments', 'ResidueTypeController@storeDataDocuments')->name('storeDataDocuments'); // padrão deveria ser POST: '/{id}/documents'
        Route::put('/{id}', 'ResidueTypeController@update')->name('update');
        Route::delete('/{id}', 'ResidueTypeController@destroy')->name('destroy');
    });

    //. Cronogram - Routes //
    Route::group(['prefix' => 'events','as' => 'events.', 'middleware' => ['auth:api']],
    function() {
        Route::get('/', 'CronogramController@index')->name('index');
        Route::get('/{year}', 'CronogramController@index')->name('year')->where(['year' => '[0-9]{4}']);
        Route::get('/{year}/{month}', 'CronogramController@index')->name('monthly')->where(['year' => '[0-9]{4}', 'month' => '[0-9]{1,2}']);
        Route::get('/next', 'CronogramController@nextEvents')->name('next');
        Route::post('/', 'CronogramController@create')->name('create');
        Route::put('/{id}', 'CronogramController@update')->name('update');
        Route::delete('/{id}', 'CronogramController@destroy')->name('destroy');
    });

    //. Providers - Routes //
    Route::group(['prefix' => 'providers', 'as' => 'providers.', 'middleware' => ['auth:api']],
    function() {
        Route::get('/', 'ProviderController@index')->name('index');
        Route::get('/{id}', 'ProviderController@get')->name('get');
        Route::post('/paginate', 'ProviderController@paginate')->name('paginate');
        Route::post('/', 'ProviderController@create')->name('create');
        Route::put('/{id}', 'ProviderController@update')->name('update');
        Route::delete('/{id}', 'ProviderController@destroy')->name('delete');
    });

    //. TrainingTopics - Routes //
    // Route::group(['prefix' => 'trainingtopics', 'as' => 'training.topics.', 'middleware' => ['auth:api']],
    // function() {
    //     Route::get('/', 'TrainingTopicController@index')->name('index');
    //     Route::post('/paginate', 'TrainingTopicController@paginate')->name('paginate');
    //     Route::post('/', 'TrainingTopicController@create')->name('create');
    //     Route::put('/{id}', 'TrainingTopicController@update')->name('update');
    //     Route::delete('/{id}', 'TrainingTopicController@delete')->name('delete'); 
    // });

    //. Trainings - Routes //
    Route::group(['prefix' => 'trainings', 'as' => 'trainings.', 'middleware' => ['auth:api']],
    function() {
        Route::get('/', 'TrainingController@index')->name('index');
        Route::get('/{id}', 'TrainingController@get')->name('get');
        Route::get('/{id}/topics', 'TrainingController@topics')->name('topics');
        Route::get('/{id}/files', 'TrainingController@files')->name('files');
        Route::get('/{id}/trainer', 'TrainingController@trainer')->name('trainer');
        Route::get('/{id}/users', 'TrainingController@summoneds')->name('summoneds');
        Route::post('/paginate', 'TrainingController@paginate')->name('paginate');
        Route::post('/', 'TrainingController@create')->name('create');
        Route::post('/{id}/topics', 'TrainingController@syncTopics')->name('syncTopics');
        Route::post('/{id}/users', 'TrainingController@addUser')->name('addUser');
        Route::put('/{id}', 'TrainingController@update')->name('update');
        Route::delete('/{id}', 'TrainingController@destroy')->name('delete'); 
        Route::delete('/{id}/users', 'TrainingController@removeUser')->name('removeUser');
    });

    //. Tickets - Routes //
    Route::group(['prefix' => 'ticket', 'as' => 'ticket.', 'middleware' => [ 'auth:api' ]],
    function() {

        Route::get('/subjects', 'TicketController@subjects')->name('subjects');
        Route::get('/status', 'TicketController@status')->name('status');

        Route::get('/{id}', 'TicketController@get')->name('get');
        Route::post('/', 'TicketController@create')->name('create');
        Route::post('/{id}/close', 'TicketController@closeTicket')->name('closeTicket');
        Route::post('/{id}/take', 'TicketController@takeTicket')->name('takeTicket');
        Route::post('/{id}/answer', 'TicketController@answerTicket')->name('answerTicket');

        Route::post('/paginate', 'TicketController@paginate')->name('paginate');
    });

    //. Checklist - Routes //
    Route::group(['prefix' => 'checklist', 'as' => 'checklist.', 'middleware' => ['auth:api']], function() {
        Route::get('/{id}', 'ChecklistController@details')->name('details');
        Route::post('/paginate', 'ChecklistController@paginate')->name('paginate');
        Route::post('/', 'ChecklistController@create')->name('create');
        Route::put('/{id}', 'ChecklistController@update')->name('update');
        Route::delete('/{id}', 'ChecklistController@delete')->name('delete');
    });

    Route::group(['prefix' => 'auditoria', 'as' => 'auditoria.', 'middleware' => ['auth:api']], function() {
        Route::post('/paginate', 'AuditoringController@paginate')->name('paginate');
        Route::get('/{id}', 'AuditoringController@details')->name('details');
    });
});
