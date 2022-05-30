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
        Route::post('/city', 'store');
    });

    Route::controller(App\Http\Controllers\CountryController::class)->group(function () {
        Route::get('/country/pagination', 'get_countries_pag');
        Route::get('/country', 'index');
        Route::get('/country/{country_id}', 'get_country')->where('country_id', '[0-9]+');
        Route::get('/country/uuid/{country_uuid}', 'get_country_uuid');
        Route::post('/country', 'store');
        Route::put('/country/{uuid}', 'update');
        Route::delete('/country/{uuid}', 'destroy');
    });

    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::post('/logout', 'logout');
    });
});

Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
