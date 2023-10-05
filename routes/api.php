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

Route::prefix('v1')->group(function () {
    Route::controller('AuthController')->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
    });

    // Route::resource('posts', 'PostController');

    Route::middleware('auth:sanctum')->group(function () {
        // Route::resource('blogs', 'BlogController');
        Route::controller('ProfileController')->group(function () {
            Route::get('/profile', 'profile');
            Route::post('/profile/store', 'store');
        });

        Route::controller('OrganizationController')->group(function () {
            Route::get('/organization', 'organization');
            Route::post('/organization/store', 'store');
        });

        Route::controller('EmailSettingController')->group(function () {
            Route::get('/smtp', 'smtp');
            Route::post('/smtp/store', 'store');
        });
    });
});