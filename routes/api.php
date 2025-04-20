<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->namespace('App\Http\Controllers\API\V1')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::middleware([JwtMiddleware::class])->group(function () {
        Route::prefix('tasks')->group(function () {
            Route::get('/', 'TaskController@index');
            Route::get('/{task}', 'TaskController@show');
            Route::get('my-tasks', 'TaskController@myTasks');
            Route::post('/', 'TaskController@create');
            Route::put('/{task}/change-status', 'TaskController@changeStatus');
            Route::put('{task}', 'TaskController@update');
            Route::delete('{task}', 'TaskController@delete');
            Route::put('/{task}/assign', 'TaskController@assign');
        });

        Route::get('user', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');
    });
});
