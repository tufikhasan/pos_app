<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

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
     * Reset Password Page view
     * @return View
     */
    function resetPasswordPage(): View {
        return view( 'pages.frontend.reset_password' );
    }
}
