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

Route::post('/login', 'API\Auth\LoginController@getToken');
Route::post('/register', 'API\Auth\RegisterController@create');

Route::middleware('auth:api')->namespace('API')->group(function (){

    Route::resource('categories', 'CategoriesController');
    Route::resource('shops', 'ShopsController');
    Route::get('/shops/dashboard/{id}', 'ShopsController@dashboard');
    Route::get('/storages/dashboard/{id}', 'StoragesController@dashboard');
    Route::resource('storages', 'StoragesController');
    Route::resource('suppliers', 'SuppliersController');
    Route::resource('supplies', 'SuppliesController');
    Route::resource('articles', 'ArticlesController');
    Route::resource('natures', 'SpendtypesController');
    Route::resource('spends', 'SpendsController');
    Route::resource('spendtypes', 'SpendtypesController');
    Route::resource('deliverances', 'DeliverancesController');
    Route::get('/deliverances/shop/{id}', 'DeliverancesController@shopDeliverance');

    Route::get('/dashboard/{date_debut}/{date_fin}', 'DashboardController@admin');
    Route::get('/dashboard/{shop_id}/{date_debut}/{date_fin}', 'DashboardController@shop');

    // Containers
    Route::get('/containers/storage/{id}', 'ContainersController@showStorageContainer');
    Route::get('/containers/shop/{id}', 'ContainersController@showShopContainer');
    Route::post('/getcontainer', 'ContainersController@getContainer');

    // Rapport de la journee
    Route::get('/rapports/{id}/{date?}', 'RapportController@rapport');

    // User's route
    Route::get('/users', 'UsersController@index')->name('users');
    Route::get('/users/{id}', 'UsersController@show');
    Route::put('/users/actif/{id}', 'UsersController@desactiveOrActiveUser')->name('users.actif');
    Route::put('/users/password/{id}', 'UsersController@changePassword')->name('users.password');
    Route::post('/users/storepicture/{id}', 'UsersController@storePicture')->name('users.storePicture');
    
    Route::post('/shop_inventaire', 'ComptaController@shop_inventaire');
    Route::post('/storage_inventaire', 'ComptaController@storage_inventaire');

    Route::get('/getpicture/{url}', 'PictureController@getPicture');

    // Notification's route
    Route::get('/notifications/{id}', 'NotificationController@getDetails');
    Route::post('/notifications/getnotifications', 'NotificationController@getNotifications');
    Route::post('/notifications/confirm', 'NotificationController@confirmed');

    // Purchases's routes    
    Route::resource('purchases', 'PurchasesController');
    Route::get('/purchases/getcontainer/{id}', 'PurchasesController@getcontainer');
});
