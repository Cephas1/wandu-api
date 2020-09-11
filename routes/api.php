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

Route::resource('categories', 'API\CategoriesController');
Route::resource('clients', 'API\ClientsController');
Route::resource('furnishers', 'API\FurnishersController');
Route::resource('articles', 'API\ArticlesController');
Route::resource('deliveries', 'API\DeliveriesController');
Route::resource('purchases', 'API\PurchasesController');

Route::post('/login', 'API\Auth\LoginController@login');

Route::middleware('auth:api')->group(function (){
//    Route::resource('categories', 'API\CategoriesController');
//    Route::resource('clients', 'API\ClientsController');
//    Route::resource('furnishers', 'API\FurnishersController');
//    Route::resource('articles', 'API\ArticlesController');
//    Route::resource('deliveries', 'API\DeliveriesController');
//    Route::resource('purchases', 'API\PurchasesController');
});
