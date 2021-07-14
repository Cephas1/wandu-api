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

Route::middleware('auth:api')->namespace('API')->group(function (){

    Route::post('/register', 'Auth\RegisterController@create');

    Route::resource('articles', 'ArticlesController');
    Route::resource('categories', 'CategoriesController');
    Route::resource('colors', 'ColorsController');
    Route::resource('shops', 'ShopsController');
    Route::resource('storages', 'StoragesController');

    Route::resource('suppliers', 'SuppliersController');
    Route::resource('supplies', 'SuppliesController');
    Route::get('/supplies/storage/{id}', 'SuppliesController@index_one');

    Route::resource('natures', 'SpendtypesController');
    Route::resource('spendtypes', 'SpendtypesController');
    Route::resource('spends', 'SpendsController');
    Route::get('/spends/storage/{id}', 'SpendsController@storage_spends');
    Route::get('/spends/shop/{id}', 'SpendsController@shop_spends');

    Route::resource('deliverances', 'DeliverancesController');
    Route::get('/deliverances/shop/{id}', 'DeliverancesController@shopDeliverance');
    Route::get('/deliverances/storage/{id}', 'DeliverancesController@storageDeliverance');

    // Dashboard
    Route::get('/shops/dashboard/{id}', 'ShopsController@dashboard');
    Route::get('/storages/dashboard/{id}', 'StoragesController@dashboard');
    Route::get('/dashboard/cashier/{shop_id}', 'ShopsController@cashier');
    Route::get('/dashboard/{date_debut}/{date_fin}', 'DashboardController@admin');
    Route::get('/dashboard/{shop_id}/{date_debut}/{date_fin}', 'DashboardController@shop');

    // Refferences
    Route::get('/refferences/storage/{id}', 'RefferencesController@getRefStorage');

    // Containers
    Route::get('/containers/storage/{id}', 'ContainersController@showStorageContainer');
    Route::get('/containers/shop/{id}', 'ContainersController@showShopContainer');
    Route::post('/getcontainer', 'ContainersController@getContainer');

    // Rapport de la journee
    Route::get('/rapports/{id}/{date?}', 'RapportController@rapport');
    // Rapport de la journee storage
    Route::get('/rapports/storage/{id}/{date?}', 'RapportController@storagesRapport');
    // Rapport de la journee shop supplies
    Route::get('/rapports/supplies/{id}/{date?}', 'RapportController@rapportSupplies');

    // User's route
    Route::get('/users', 'UsersController@index')->name('users');
    Route::get('/users/{id}', 'UsersController@show');
    Route::put('/users/actif/{id}', 'UsersController@desactiveOrActiveUser')->name('users.actif');
    Route::put('/users/password/{id}', 'UsersController@changePassword')->name('users.password');
    Route::get('/users/password/reset/{id}', 'UsersController@resetPassword');

    // Inventory
    Route::post('/inventaire/shop', 'ComptaController@shop_inventaire');
    Route::post('/inventaire/storage', 'ComptaController@storage_inventaire');
    Route::post('/inventaire/all', 'ComptaController@all_inventaire');

    // Picture's routes
    Route::get('/getpicture/{path}', 'PictureController@getPicture');
    Route::post('/picture/users/{id}', 'UsersController@storePicture');
    Route::post('/picture/articles/{id}', 'ArticlesController@storePicture');
    Route::post('/picture/suppliers/{id}', 'SuppliersController@storePicture');
    Route::post('/picture/storages/{id}', 'StoragesController@storePicture');
    Route::post('/picture/shops/{id}', 'ShopsController@storePicture');

    // Notification's route
    Route::get('/notifications/{id}', 'NotificationController@getDetails');
    Route::post('/notifications/getnotifications', 'NotificationController@getNotifications');
    Route::post('/notifications/confirm', 'NotificationController@confirmed');

    // Purchases's routes
    Route::resource('purchases', 'PurchasesController');
    Route::get('/purchases/getcontainer/{id}', 'PurchasesController@getcontainer');
});
