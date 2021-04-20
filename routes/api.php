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


Route::namespace('Api')->group(function (){

        Route::prefix('v1')->group(function () {

            Route::prefix('auth')->group(function () {

                // register and login routes
                  Route::post('/register', 'UserController@register');
                  Route::post('/login', 'UserController@login');

                  //protected routes
                  Route::middleware(['apipass'])->group(function () {
                        // fetching user profile route
                        Route::get('/user', 'UserController@profile');

                  });
            });

            //protected routes
            Route::middleware(['apipass'])->group(function () {

                // gist operations routes
                Route::apiResource('gists', GistController::class);
             });
        });
});



