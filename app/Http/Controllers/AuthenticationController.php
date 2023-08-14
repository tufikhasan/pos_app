<?php

namespace App\Http\Controllers;

use App\Helper\Jwt_token;
use App\Mail\OtpSendMail;
use App\Models\Shop;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller {
    /**
     * Sign Up Page
     * @return View
     */
    public function signUpPage(): View {
        return view( 'auth.sign_up' );
    }

    /**
     * Sign up
     * @param Request
     * @return JsonResponse
     */
    public function signUp( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'shop_name' => 'required',
                'name'      => 'required',
                'email'     => 'required|email|unique:users,email',
                'mobile'    => 'required|unique:users,mobile',
                'password'  => 'required',
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 200 );
            }

            DB::beginTransaction();
            $shop = Shop::create( $request->only( 'shop_name' ) );
            User::create( array_merge( $request->only( 'name', 'email', 'mobile' ), ['shop_id' => $shop->id, 'password' => Hash::make( $request->password )] ) );
            DB::commit();
            return response()->json( ['status' => 'success', 'message' => 'Registration Successfully'], 201 );
        } catch ( \Throwable $th ) {
            DB::rollBack();
            return response()->json( ['status' => 'failed', 'message' => 'Registration Failed'], 400 );
        }

    }

    /**
     * Sign In Page
     * @return View
     */
    public function signInPage(): View {
        return view( 'auth.sign_in' );
    }

    /**
     * Sign In
     * @param Request
     * @return JsonResponse
     */
    public function signIn( Request $request ): JsonResponse {
        try {
            $user = User::where( 'email', $request->credential )->orWhere( 'mobile', $request->credential )->first();
            if ( !$user ) {
                return response()->json( ['status' => 'Invalid', 'message' => 'Invalid Creadintials'], 401 );
            }

            if ( Hash::check( $request->password, $user->password ) ) {
                $data = ['id' => $user->id, 'shop_id' => $user->shop_id, 'email' => $user->email, 'role' => $user->role];
                $token = Jwt_token::generate_token( $data );

                return response()->json( ['status' => 'success', 'message' => 'Successfully Sign In'], 200 )->cookie( 'token', $token, 60 * 60 * 24 );
            }
            return response()->json( ['status' => 'Invalid', 'message' => 'Invalid Credentials'], 401 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 401 );
        }

    }

    /**
     * Sign Out
     * @return RedirectResponse
     */
    public function signout(): RedirectResponse {
        Cart::destroy();
        return redirect()->route( 'signin.page' )->cookie( 'token', '', -1 );

    }

    /**
     * Send OTP Page view
     * @return View
     */
    function sendOtpPage(): View {
        return view( 'auth.send_otp' );
    }

    /**
     * Send Otp
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function sendOtp( Request $request ): JsonResponse {
        try {
            $otp = rand( 100000, 999999 );
            $email = $request->email;

            $user = User::where( 'email', $email )->first();

            if ( !$user ) {
                return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 401 );
            }

            //OTP send
            Mail::to( $email )->send( new OtpSendMail( $otp ) );
            //update user table otp
            $user->update( ['otp' => $otp] );

            return response()->json( ['status' => 'success', 'message' => "6 digit otp code send. Please check your mail"], 200 );

        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 401 );
        }
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
     * Verify OTP Page view
     * @return View
     */
    function verifyOtpPage(): View {
        return view( 'auth.verify_otp' );
    }

    /**
     * Verify Otp
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function verifyOtp( Request $request ): JsonResponse {
        try {
            $otp = $request->otp;
            $email = $request->email;

            $user = User::where( 'email', $email )->where( 'otp', $otp )->first();

            //user find based on email & otp
            if ( !$user ) {
                return response()->json( ['status' => 'Failed', 'message' => 'Please enter a valid otp'], 400 );
            }

            //Otp expired after 3 minutes
            $expirationTime = strtotime( $user->updated_at ) + ( 60 * 3 );
            if ( time() > $expirationTime ) {
                //otp update
                $user->update( ['otp' => 0] );
                return response()->json( ['status' => 'Failed', 'message' => 'Your Otp expired'], 400 );
            }
            //otp update
            $user->update( ['otp' => 0] );
            //create password reset token
            $token = Jwt_token::generate_token( ['email' => $user->email], 300 );
            return response()->json( ['status' => 'success', 'message' => "Your Otp verify Successfully", 'token' => $token], 200 )->cookie( 'reset_token', $token, ( 60 * 3 ) ); //expired after 5 minutes

        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }

    /**
     * Reset Password Page view
     * @return View
     */
    function resetPasswordPage(): View {
        return view( 'auth.reset_password' );
    }

    /**
     * Reset Password
     * @param Request $request
     * @return mixed
     *
     */
    public function resetPassword( Request $request ) {
        try {

            //Password validation
            $validator = Validator::make( $request->all(), [
                'password'         => 'required|min:4',
                'confirm_password' => 'required|same:password',
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'Failed', 'message' => $validator->errors()], 400 );
            }

            $email = $request->header( 'email' );

            User::where( 'email', $email )->update( ['password' => Hash::make( $request->password )] );
            return response()->json( ['status' => 'success', 'message' => 'Password Update Successfully'], 200 )->cookie( 'reset_token', '', -1 );

        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }
}
