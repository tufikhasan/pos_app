<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FrontEndController extends Controller {
    /**
     * Login Page view
     * @return View
     */
    function loginPage(): View {
        return view( 'pages.frontend.login' );
    }

    /**
     * Register Page view
     * @return View
     */
    function registerPage(): View {
        return view( 'pages.frontend.register' );
    }

    /**
     * Forgot Password Page view
     * @return View
     */
    function forgetPage(): View {
        return view( 'pages.frontend.forget_password' );
    }

    /**
     * Verify OTP Page view
     * @return View
     */
    function verifyOtpPage(): View {
        return view( 'pages.frontend.verify_otp' );
    }
    /**
     * Show countdown time in verify otp page
     * @return mixed
     */
    function showCountdown( Request $request ) {
        $data = User::where( 'email', $request->email )->select( 'updated_at' )->first();
        $updated_time = strtotime( $data->updated_at ) + ( 60 * 3 );
        $current_time = time();

        $interval = abs( $current_time - $updated_time );
        $minutes = floor( $interval / 60 ); // Get the number of minutes
        $seconds = $interval % 60; // Get the remaining seconds

        return [
            'update_time'  => $updated_time,
            'minutes'      => $minutes,
            'seconds'      => $seconds,
            'current_time' => $current_time,
        ];
    }

    /**
     * Reset Password Page view
     * @return View
     */
    function resetPasswordPage(): View {
        return view( 'pages.frontend.reset_password' );
    }
}
