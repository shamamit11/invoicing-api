<?php
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
            Route::post('/profile/updatePassword', 'updatePassword');
        });

        Route::controller('OrganizationController')->group(function () {
            Route::get('/organization', 'organization');
            Route::post('/organization/store', 'store');
        });

        Route::controller('EmailSettingController')->group(function () {
            Route::get('/smtp', 'smtp');
            Route::post('/smtp/store', 'store');
        });

        Route::controller('SavedItemController')->group(function () {
            Route::get('/saved-items', 'index');
            Route::get('/saved-item/show', 'show');
            Route::post('/saved-item/store', 'store');
            Route::post('/saved-item/updateStatus', 'updateStatus');
            Route::post('/saved-item/delete', 'delete');
        });

        Route::controller('CustomerController')->group(function () {
            Route::get('/customers', 'index');
            Route::get('/customer/show', 'show');
            Route::post('/customer/store', 'store');
            Route::post('/customer/updateStatus', 'updateStatus');
            Route::post('/customer/delete', 'delete');
        });

        Route::controller('ReceiptController')->group(function () {
            Route::get('/receipts', 'index');
            Route::get('/receipt/show', 'show');
            Route::post('/receipt/store', 'store');
            Route::post('/receipt/updateStatus', 'updateStatus');
            Route::post('/receipt/delete', 'delete');
        });
    });
});