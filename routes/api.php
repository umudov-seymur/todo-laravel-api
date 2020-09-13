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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['middleware' => ['json']], function () {
        Route::apiResource('todos', 'Api\TodoController');
        Route::post('/todos/check-all', 'Api\TodoController@updateConfirmed');
        Route::post('/todos/delete-completed', 'Api\TodoController@destroyCompleted');
    });

    Route::delete('/logout', 'Api\AuthController@logout');
});

Route::post('/login', 'Api\AuthController@login');
Route::post('/register', 'Api\AuthController@register');
