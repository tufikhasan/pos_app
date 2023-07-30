<?php

namespace App\Http\Controllers;

use App\Helper\JWT_TOKEN;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller {
    /**
     * Get All Users
     * @return Collection
     */
    public function allUsers(): Collection {
        return User::latest()->get();
    }

    /**
     * Register Page view
     * @return View
     */
    function registerPage(): View {
        return view( 'pages.auth.register' );
    }

    /**
     * Registration user
     * @param Request $request
     * @return JsonResponse
     */
    public function registration( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'email' => 'required|email|unique:users,email',
            ], [
                'email.unique' => 'Already Have an account',
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'Failed', 'message' => $validator->errors()], 403 );
            }
            User::create(
                array_merge( $request->only( 'first_name', 'last_name', 'email', 'mobile' ), ['password' => Hash::make( $request->password )] )
            );
            return response()->json( ['status' => 'success', 'message' => 'User Registration Successful'], 201 );

        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'failed', 'message' => 'Registration failed'] );
        }
    }

    /**
     * Login Page view
     * @return View
     */
    function loginPage(): View {
        return view( 'pages.auth.login' );
    }

    /**
     * User Login Method
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function userLogin( Request $request ): JsonResponse {
        try {
            $user = User::where( 'email', $request->email )->first();
            if ( !$user ) {
                return response()->json( ['status' => 'Invalid', 'message' => 'Invalid Credentials'], 401 );
            }

            if ( Hash::check( $request->password, $user->password ) ) {
                $token = JWT_TOKEN::create_token( $user->email, $user->id );
                return response()->json( ['status' => 'success', 'message' => 'Login Successful'], 200 )->cookie( 'token', $token, 60 * 60 * 24 );
            } else {
                return response()->json( ['status' => 'Invalid', 'message' => 'Invalid Credentials'], 401 );
            }

        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }

    /**
     * User Logout Method
     * @param Request $request
     *
     */
    public function userLogout() {
        return redirect()->route( 'login' )->cookie( 'token', '', -1 );
    }

    /**
     * Forgot Password Page view
     * @return View
     */
    function forgetPage(): View {
        return view( 'pages.auth.forget_password' );
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
            Mail::to( $email )->send( new OTPMail( $otp ) );
            //update user table otp
            $user->update( ['otp' => $otp] );

            return response()->json( ['status' => 'success', 'message' => "6 digit otp code send this {$email} email. Please check your mail"], 200 );

        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }

    /**
     * Verify OTP Page view
     * @return View
     */
    function verifyOtpPage(): View {
        return view( 'pages.auth.verify_otp' );
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
            $reset_token = JWT_TOKEN::reset_token( $email );
            return response()->json( ['status' => 'success', 'message' => "Your Otp verify Successfully"], 200 )->cookie( 'token', $reset_token, ( 60 * 5 ) ); //expired after 5 minutes

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
        return view( 'pages.auth.reset_password' );
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
            return response()->json( ['status' => 'success', 'message' => 'Password Update Successfully'], 200 )->cookie( 'token', '', -1 );

        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }

    /**
     * Profile Page view
     * @return View
     */
    function profilePage(): View {
        return view( 'pages.auth.profile' );
    }

    /**
     * Profile Details
     * @param Request $request
     * @return object
     */
    function profileDetails( Request $request ) {
        $user = User::where( ['id' => $request->header( 'id' ), 'email' => $request->header( 'email' )] )->first();
        return $user;
    }
    /**
     * Profile Details update
     * @param Request $request
     * @return JsonResponse
     */
    function profileUpdate( Request $request ): JsonResponse{
        User::where( ['id' => $request->header( 'id' ), 'email' => $request->header( 'email' )] )->update( [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'mobile'     => $request->mobile,
        ] );
        return response()->json( ['status' => 'success', 'message' => 'Profile Update Successfully'], 200 );
    }
    /**
     * Profile Image Update
     * @param Request $request
     * @return JsonResponse
     */
    function profileImgUpdate( Request $request ): JsonResponse{
        $user = User::findOrFail( $request->header( 'id' ) );

        $image = $request->file( 'image' );
        if ( $request->hasFile( 'image' ) ) {
            $imageUrl = 'upload/avtar/' . hexdec( uniqid() ) . '.' . $image->getClientOriginalExtension();

            if ( $user->image && file_exists( public_path( $user->image ) ) ) {
                unlink( public_path( $user->image ) );
            }

            $image->move( public_path( 'upload/avtar' ), $imageUrl );
            $user->image = $imageUrl;
            $user->save();

            return response()->json( ['status' => 'success', 'message' => 'Profile Image Updated Successfully'], 200 );
        }
        return response()->json( ['status' => 'not-change', 'message' => 'Nothing changes'], 200 );
    }

    /**
     * Change Password view
     * @return View
     */
    function changePassword(): View {
        return view( 'pages.auth.change_password' );
    }
    /**
     * Profile Image Update
     * @param Request $request
     * @return JsonResponse
     */
    function updatePassword( Request $request ): JsonResponse{
        $user = User::findOrFail( $request->header( 'id' ) );

        if ( Hash::check( $request->old_password, $user->password ) ) {
            $user->update( ['password' => Hash::make( $request->new_password )] );
            return response()->json( ['status' => 'success', 'message' => 'Password Updated Successfully'], 200 );
        } else {
            return response()->json( ['status' => 'failed', 'message' => 'OLD Password Not Match'], 200 );
        }
    }
}
