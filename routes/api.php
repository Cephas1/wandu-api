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

Route::middleware('auth:api')->group(function (){

    Route::resource('categories', 'API\CategoriesController');
    Route::resource('shops', 'API\ShopsController');
    Route::get('/shops/dashboard/{id}', 'API\ShopsController@dashboard');
    Route::resource('storages', 'API\StoragesController');
    Route::resource('suppliers', 'API\SuppliersController');
    Route::resource('supplies', 'API\SuppliesController');
    Route::resource('articles', 'API\ArticlesController');
    Route::resource('natures', 'API\SpendtypesController');
    Route::resource('spends', 'API\SpendsController');
    Route::resource('spendtypes', 'API\SpendtypesController');
    Route::resource('deliverances', 'API\DeliverancesController');
    Route::get('/deliverances/shop/{id}', 'API\DeliverancesController@shopDeliverance');

    // Containers
    Route::get('/containers/storage/{id}', 'API\ContainersController@showStorageContainer');
    Route::get('/containers/shop/{id}', 'API\ContainersController@showShopContainer');
    Route::post('/getcontainer', 'API\ContainersController@getContainer');

    // Rapport de la journee
    Route::get('/rapports/{id}/{date?}', 'API\RapportController@rapport');

    // User's route
    Route::post('/register', 'API\Auth\RegisterController@create');
    Route::get('/users', 'API\UsersController@index')->name('users');
    Route::put('/users/actif/{id}', 'API\UsersController@desactiveOrActiveUser')->name('users.actif');
    Route::put('/users/password/{id}', 'API\UsersController@changePassword')->name('users.password');
    Route::post('/users/storepicture/{id}', 'API\UsersController@storePicture')->name('users.storePicture');
    
    Route::post('/shop_inventaire', 'API\ComptaController@shop_inventaire');
    Route::post('/storage_inventaire', 'API\ComptaController@storage_inventaire');

    Route::post('/getpicture', 'API\PictureController@getPicture');

    // Notification's route
    Route::get('/notifications/{id}', 'API\NotificationController@getDetails');
    Route::post('/notifications/getnotifications', 'API\NotificationController@getNotifications');
    Route::post('/notifications/confirm', 'API\NotificationController@confirmed');

    // Purchases's routes    
    Route::resource('purchases', 'API\PurchasesController');
    Route::get('/purchases/getcontainer/{id}', 'API\PurchasesController@getcontainer');
});
