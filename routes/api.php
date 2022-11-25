<?php

use App\Http\Controllers\Admin\AdminParentLandController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ParentLandController;
use App\Http\Controllers\ProductLineController;
use App\Http\Controllers\StoreController;
use App\Models\ParentLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', function ($api) {
    $api->get('hello', function () {
        return 'Hello Stores API';
    });

    $api->get('categories', 'App\Http\Controllers\CategoryController@index');
    $api->get('categories/{id}', 'App\Http\Controllers\CategoryController@show');

    $api->post('/signup', 'App\Http\Controllers\UserController@store');

    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('/signup', 'App\Http\Controllers\UserController@store');
        $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login');
        $api->group(['middleware' => 'jwt.auth'], function ($api) {
            $api->post('/token/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
            $api->post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
        });
    });

    $api->group(['middleware' => ['role:super-admin'], 'prefix' => 'admin'], function ($api) {
        $api->resource('users', AdminUserController::class);
        $api->get('users/{id}/parentlands', 'App\Http\Controllers\Admin\AdminParentLandController@index');
        $api->post('users/{id}/parentlands', 'App\Http\Controllers\Admin\AdminParentLandController@store');
        // $api->resource('users/{user}/parentlands', AdminParentLandController::class);
        // $api->post('users/{id}/suspend', 'App\Http\Controllers\Admin\AdminUserController@suspend');
        // $api->post('users/{id}/activate', 'App\Http\Controllers\Admin\AdminUserController@activate');
        // $api->get('users/{id}/roles', 'App\Http\Controllers\Admin\AdminRolesController@show');
        // $api->get('users/{id}/permissions', 'App\Http\Controllers\Admin\AdminPermissionsController@show');
        // $api->post('users/{id}/roles', 'App\Http\Controllers\Admin\AdminRolesController@changeRole');
        // $api->post('products/categories', 'App\Http\Controllers\CategoryController@store');
        // $api->put('products/categories/{id}', 'App\Http\Controllers\CategoryController@update');
        // $api->delete('products/categories/{id}', 'App\Http\Controllers\CategoryController@destroy');
    });

    $api->group(['middleware' => ['role:upt-owner'], 'prefix' => 'upt'], function ($api) {
        $api->resource('parentlands', ParentLandController::class);
        // $api->group(['middleware' => 'isStoreOwner'], function ($api) {
        //     $api->resource('stores', StoreController::class);
        //     // $api->resource('stores/{store}/brands', BrandController::class);
        //     // $api->resource('stores/{store}/brands/{brands}/productlines', ProductLineController::class);
        // });
    });
});
