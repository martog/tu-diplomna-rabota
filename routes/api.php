<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:api',
], function ($router) {

    /* AuthController */
    Route::post('auth/login', 'AuthController@login')->withoutMiddleware('auth:api');
    Route::post('auth/logout', 'AuthController@logout');
    Route::post('auth/refresh', 'AuthController@refresh');


    /* UsersController */
    Route::post('users/register', 'UserController@register')->withoutMiddleware('auth:api');
    Route::put('users/update', 'UserController@update');


    /* DeviceController */
    Route::post('devices/test', 'DeviceController@test');
    Route::post('controllers', 'DeviceController@addController');
});
