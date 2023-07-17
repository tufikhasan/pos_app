<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller( UserController::class )->group( function () {
    Route::get( '/users', 'allUsers' );
    Route::post( '/register', 'registration' );
    Route::post( '/user/login', 'userLogin' );
    Route::post( '/send/otp', 'sendOtp' );
    Route::post( '/verify/otp', 'verifyOtp' );
    Route::post( '/reset/password', 'resetPassword' )->middleware( 'verify.token' );
} );

Route::get( '/dashboard', function () {
    return view( 'layouts.dashboard' );
} );

Route::get( '/', function () {
    return view( 'pages.frontend.login' );
} )->name( 'login' );

Route::get( '/register', function () {
    return view( 'pages.frontend.register' );
} )->name( 'register' );

Route::get( '/forgot/password', function () {
    return view( 'pages.frontend.forgot_password' );
} )->name( 'forgot.password' );

Route::get( '/verify/otp', function () {
    return view( 'pages.frontend.verify_otp' );
} )->name( 'verify.otp' );

Route::get( '/reset/password', function () {
    return view( 'pages.frontend.reset_password' );
} )->name( 'reset.password' );
