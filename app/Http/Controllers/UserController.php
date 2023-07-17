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
}
