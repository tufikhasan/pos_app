<?php

namespace App\Http\Controllers;

use App\Helper\JWT_TOKEN;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
    /**
     * Get All Users
     * @return Collection
     */
    public function allUsers(): Collection {
        return User::latest()->get();
    }

    /**
     * Registration user
     * @param Request $request
     * @return JsonResponse
     */
    public function registration( Request $request ): JsonResponse {
        try {
            $request->validate( [
                'first_name' => 'required',
                'email'      => 'required|email|unique:users,email',
            ], [
                'email.unique' => 'Already Have an account',
            ] );
            User::create(
                array_merge( $request->only( 'first_name', 'last_name', 'email', 'mobile' ), ['password' => Hash::make( $request->password )] )
            );
            return response()->json( ['status' => 'success', 'message' => 'User Registration Successful'], 201 );

        } catch ( \Illuminate\Database\QueryException $ex ) {
            // Handle database query exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Database connection error'], 500 );
        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Registration failed'], 500 );
        }
    }
    /**
     * User Login Method
     * @param Request $request
     * @return mixed
     *
     */
    public function userLogin( Request $request ) {
        try {
            $user = User::where( 'email', $request->email )->first();
            if ( !$user ) {
                return response()->json( ['status' => 'Invalid', 'message' => 'Invalid Credentials'], 401 );
            }

            if ( Hash::check( $request->password, $user->password ) ) {
                $token = JWT_TOKEN::create_token( $user->email );
                return response()->json( ['status' => 'success', 'message' => 'Login Successful', 'token' => $token], 200 );
            } else {
                return response()->json( ['status' => 'Invalid', 'message' => 'Invalid Credentials'], 401 );
            }

        } catch ( \Illuminate\Database\QueryException $ex ) {
            // Handle database query exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Database connection error'], 500 );
        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }

    /**
     * Send Otp
     * @param Request $request
     * @return mixed
     *
     */
    public function sendOtp( Request $request ) {
        try {
            $otp = rand( 100000, 999999 );
            $email = $request->email;

            $user = User::where( 'email', $email )->first();

            if ( !$user ) {
                return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
            }

            //OTP send
            Mail::to( $email )->send( new OTPMail( $otp ) );
            //update user table otp
            $user->update( ['otp' => $otp] );

            return response()->json( ['status' => 'success', 'message' => "6 digit otp code send this {$email} email. Please check your mail"], 200 );

        } catch ( \Illuminate\Database\QueryException $ex ) {
            // Handle database query exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Database connection error'], 500 );
        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }

    /**
     * Verify Otp
     * @param Request $request
     * @return mixed
     *
     */
    public function verifyOtp( Request $request ) {
        try {
            //Otp length validation
            $validator = Validator::make( $request->all(), [
                'otp' => 'required|min:6',
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'Failed', 'message' => $validator->errors()], 400 );
            }

            $otp = $request->otp;
            $email = $request->email;

            $user = User::where( 'email', $email )->where( 'otp', $otp )->first();

            //user find based on email & otp
            if ( !$user ) {
                return response()->json( ['status' => 'Failed', 'message' => 'Please enter a valid otp'], 400 );
            }

            // //Otp length validation
            // if ( 0 == $otp || strlen( $otp ) != 6 ) {
            //     return response()->json( ['status' => 'Failed', 'message' => 'Please enter a valid otp'], 400 );
            // }

            //Otp expired after 5 minutes
            $expirationTime = strtotime( $user->updated_at ) + ( 5 * 60 );
            if ( time() > $expirationTime ) {
                //otp update
                $user->update( ['otp' => 0] );
                return response()->json( ['status' => 'Failed', 'message' => 'Your Otp expired'], 400 );
            }
            //otp update
            $user->update( ['otp' => 0] );
            //create password reset token
            $reset_token = JWT_TOKEN::reset_token( $email );
            return response()->json( ['status' => 'success', 'message' => "Your Otp verify Successfully", 'reset_token' => $reset_token], 200 );

        } catch ( \Illuminate\Database\QueryException $ex ) {
            // Handle database query exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Database connection error'], 500 );
        } catch ( \Throwable $th ) {
            // Handle other exceptions
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        }
    }
}
