<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller( UserController::class )->group( function () {
    Route::get( '/users', 'allUsers' );
    Route::post( '/register', 'registration' );
    Route::post( '/user/login', 'userLogin' );
    Route::post( '/send/otp', 'sendOtp' );
    Route::post( '/verify/otp', 'verifyOtp' );
} );
