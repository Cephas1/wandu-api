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

Route::post('/login', 'API\Auth\LoginController@login');

Route::middleware('auth:api')->group(function (){
    
    //Route::resource('categories', 'API\CategoriesController');
    Route::resource('shops', 'API\ShopsController');
    Route::resource('storages', 'API\StoragesController');
    Route::resource('suppliers', 'API\SuppliersController');
    Route::resource('supplies', 'API\SuppliesController');
    Route::resource('articles', 'API\ArticlesController');
    Route::resource('natures', 'API\SpendtypesController');
    Route::resource('purchases', 'API\PurchasesController');
    Route::resource('spends', 'API\SpendsController');
    Route::resource('spendtypes', 'API\SpendtypesController');
    Route::resource('deliverances', 'API\DeliverancesController');
    Route::get('/deliverances/shop/{id}', 'API\DeliverancesController@shopDeliverance');
    Route::get('/containers/storage/{id}', 'API\ContainersController@showStorageContainer');
    Route::get('/containers/shop/{id}', 'API\ContainersController@showShopContainer');
    Route::get('/rapports', 'API\RapportController@index');
});
