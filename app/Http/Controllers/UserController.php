<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
}
