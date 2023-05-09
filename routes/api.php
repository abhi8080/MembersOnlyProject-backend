<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::group(['namespace' => 'App\Http\Controllers'], function() {
    Route::post('auth/register', 'UserController@addUser');
    Route::post('auth/login', 'UserController@login');


    Route::get('messages', 'MessageController@getMessages')->middleware('App\Http\Middleware\AuthorizeRequestMiddleware');
    Route::post('messages', 'MessageController@addMessage')->middleware('App\Http\Middleware\AuthorizeRequestMiddleware');
    Route::delete('messages/{id}', 'MessageController@deleteMessage')->middleware('App\Http\Middleware\AuthorizeRequestMiddleware');
    Route::put('user/{id}', 'UserController@updateUser')->middleware('App\Http\Middleware\AuthorizeRequestMiddleware');
    Route::get('user/{id}', 'UserController@getUserAdminStatus')->middleware('App\Http\Middleware\AuthorizeRequestMiddleware');
});