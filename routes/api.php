<?php

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

Route::group(['middleware' => 'auth:api'], function () {
    Route::controller(App\Http\Controllers\CityController::class)->group(function () {
        Route::get('/city', 'index');
    });
});

Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
