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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/list', 'BookController@index')->name('list');
Route::match([ 'get', 'post' ], '/create', 'BookController@create')->name('create');
Route::post('/delete', 'BookController@delete')->name('delete');

Route::group([ 'prefix' => 'account' ], function() {
    Route::get('/', 'AccountController@index')->name('account');
    Route::post('/update', 'AccountController@update')->name('account/update');
});

Auth::routes();
