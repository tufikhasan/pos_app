<?php

use App\Http\Controllers\FrontEndController;
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
} )->name( 'dashboard' );

Route::controller( FrontEndController::class )->group( function () {
    Route::get( '/', 'loginPage' )->name( 'login' );
    Route::get( '/register', 'registerPage' )->name( 'register' );
    Route::get( '/send/otp', 'forgetPage' )->name( 'forgot.password' );
    Route::get( '/verify/otp', 'verifyOtpPage' )->name( 'verify.otp' );
    Route::get( '/countdown/{email}', 'showCountdown' )->name( 'countdown' );
    Route::get( '/reset/password', 'resetPasswordPage' )->name( 'reset.password' );
} );
