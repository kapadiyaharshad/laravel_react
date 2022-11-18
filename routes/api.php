<?php

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

Route::post('register','Api\AuthController@register');
Route::post('login','Api\AuthController@login');
//Admin panel access
Route::middleware(['auth:sanctum','isApiAmin'])->group(function () {
    Route::get('checkAuthenticated','Api\AuthController@checkAuthenticated');   
    Route::get('view-category','Admin\CategoryController@index');
    Route::post('store-category','Admin\CategoryController@store');
    Route::get('edit-category/{id}','Admin\CategoryController@edit');
    Route::put('update-category', 'Admin\CategoryController@update');
    Route::delete('delete-category/{id}','Admin\CategoryController@delete');
    Route::get('category-list','Admin\CategoryController@List');

    //prouduct
    Route::post('store-product','Admin\ProductController@store');
    Route::get('list-product','Admin\ProductController@index');
    Route::get('edit-product/{id}','Admin\ProductController@edit');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout','Api\AuthController@logout');  
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
