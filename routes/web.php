<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::namespace('WEB')->group(function (){

    Route::get('basket', 'BasketsController@index')->name('basket.index');
    Route::get('shops', 'ShopsController@index')->name('shops.index');
    Route::get('shops/{id}', 'ShopsController@show')->name('shops.show');
    Route::get('products', 'ProductsController@index')->name('products.index');
    Route::get('products/{id}', 'ProductsController@show')->name('products.show');
    Route::post('products/search', 'ProductsController@show')->name('products.search');

    Route::get('/about', function () { return view('about'); })->name('about');
    Route::get('/contact', function () { return view('contact'); })->name('contact');
    Route::get('/home', function () { return view('home'); })->name('home');
    //Route::get('/home', 'HomeController@index')->name('home');
});

Route::middleware(['auth'])->namespace('WEB')->group(function (){
    Route::get('/basket', 'BasketsController@index')->name('basket.index');
    Route::post('/basket', 'BasketsController@store')->name('basket.store');
});
