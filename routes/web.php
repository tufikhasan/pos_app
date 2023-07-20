<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get( '/dashboard', function () {
    return view( 'layouts.dashboard' );
} )->name( 'dashboard' )->middleware( 'verify.token' );

Route::controller( AuthenticationController::class )->group( function () {
    Route::middleware( 'redirect.VerifyToken' )->group( function () {
        Route::get( '/register', 'registerPage' )->name( 'register' );
        Route::post( '/register', 'registration' );

        Route::get( '/', 'loginPage' )->name( 'login' );
        Route::post( '/user/login', 'userLogin' );

        Route::get( '/send/otp', 'forgetPage' )->name( 'forgot.password' );
        Route::post( '/send/otp', 'sendOtp' );

        Route::get( '/verify/otp', 'verifyOtpPage' )->name( 'verify.otp' );
        Route::get( '/countdown/{email}', 'showCountdown' )->name( 'countdown' )->withoutMiddleware( 'redirect.VerifyToken' );
        Route::post( '/verify/otp', 'verifyOtp' );
    } );

    Route::middleware( 'verify.token' )->group( function () {
        Route::get( '/users', 'allUsers' );
        Route::post( '/reset/password', 'resetPassword' );
        Route::get( '/reset/password', 'resetPasswordPage' )->name( 'reset.password' );
    } );

    Route::get( '/user/logout', 'userLogout' )->name( 'logout' );

} );
