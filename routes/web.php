<?php

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

Route::get('/', 'HomeController@index');
Route::get('/list.json', 'BookController@list');
Route::post('/create', 'BookController@create');
Route::post('/edit', 'BookController@edit');
Route::get('/fetch', 'BookController@fetch');
Route::post('/delete', 'BookController@delete');
Route::get('/help', 'HomeController@help');
Route::get('/privacy-policy', 'HomeController@privacyPolicy');
Route::get('/images/P/{path}', 'BookController@fetchImage');

Route::group([ 'prefix' => 'contact' ], function () {
    Route::match([ 'get', 'post' ], '/', 'HomeController@contact');
    Route::post('/submit', 'HomeController@contactSubmit');
});

Route::group([ 'prefix' => 'settings' ], function () {
    Route::redirect('/', '/settings/account');
    Route::get('/all.json', 'SettingController@all');

    Route::group([ 'prefix' => 'account' ], function () {
        Route::get('/', 'Setting\AccountController@index');
        Route::post('/update', 'Setting\AccountController@update');
        Route::get('/delete', 'Setting\AccountController@delete');
        Route::post('/delete', 'Setting\AccountController@confirmDelete');
    });

    Route::group([ 'prefix' => 'shelves' ], function () {
        Route::get('/', 'Setting\ShelfController@index');
    });

    Route::group([ 'prefix' => 'display' ], function () {
        Route::get('/', 'Setting\DisplayController@index');
        Route::post('/update', 'Setting\DisplayController@update');
    });
});

Auth::routes([ 'verify' => true ]);
