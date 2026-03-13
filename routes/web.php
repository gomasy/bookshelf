<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Setting\DisplayController;
use App\Http\Controllers\Setting\ShelfController;
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

Route::get('/', [HomeController::class, 'index']);
Route::get('/list.json', [BookController::class, 'list']);
Route::post('/create', [BookController::class, 'create']);
Route::post('/detect', [BookController::class, 'detectImage']);
Route::post('/edit', [BookController::class, 'edit']);
Route::get('/fetch', [BookController::class, 'fetch']);
Route::post('/delete', [BookController::class, 'delete']);
Route::post('/move', [BookController::class, 'move']);
Route::get('/help', [HomeController::class, 'help']);
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy']);
Route::get('/images/P/{path}', [BookController::class, 'fetchImage']);

Route::group([ 'prefix' => 'contact' ], function () {
    Route::match([ 'get', 'post' ], '/', [HomeController::class, 'contact']);
    Route::post('/submit', [HomeController::class, 'contactSubmit']);
});

Route::group([ 'prefix' => 'settings' ], function () {
    Route::redirect('/', '/settings/account');
    Route::get('/all.json', [SettingController::class, 'all']);

    Route::group([ 'prefix' => 'account' ], function () {
        Route::get('/', [AccountController::class, 'index']);
        Route::post('/update', [AccountController::class, 'update']);
        Route::get('/delete', [AccountController::class, 'delete']);
        Route::post('/delete', [AccountController::class, 'confirmDelete']);
    });

    Route::group([ 'prefix' => 'shelves' ], function () {
        Route::get('/', [ShelfController::class, 'index']);
        Route::post('/create', [ShelfController::class, 'create']);
        Route::post('/delete', [ShelfController::class, 'delete']);
    });

    Route::group([ 'prefix' => 'display' ], function () {
        Route::get('/', [DisplayController::class, 'index']);
        Route::post('/update', [DisplayController::class, 'update']);
    });
});

Auth::routes([ 'verify' => true ]);
