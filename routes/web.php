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
Route::get('/list.json', 'BookController@index');
Route::match([ 'get', 'post' ], '/create', 'BookController@create');
Route::post('/edit', 'BookController@edit');
Route::post('/delete', 'BookController@delete');

Route::group([ 'prefix' => 'settings' ], function () {
    Route::redirect('/', '/settings/account');
    Route::group([ 'prefix' => 'account' ], function () {
        Route::get('/', 'AccountController@index');
        Route::post('/update', 'AccountController@update');
        Route::get('/delete', 'AccountController@delete');
        Route::post('/delete', 'AccountController@confirm_delete');
    });
});

Auth::routes();
