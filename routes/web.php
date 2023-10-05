<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verify-user/{token}', 'CommonController@verifyUserEmail')->name('verify-user-email');
Route::get('/user-verified', 'CommonController@userVerified')->name('user-verified');
Route::get('/user-not-verified', 'CommonController@userNotVerified')->name('user-not-verified');
