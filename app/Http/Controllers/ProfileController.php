<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller {
    /**
     * Profile Page view
     * @return View
     */
    function profilePage(): View {
        return view( 'profile.profile' );
    }

    /**
     * Profile Details
     * @param Request $request
     * @return object
     */
    function profileDetails( Request $request ) {
        $user = User::where( ['id' => $request->header( 'id' ), 'shop_id' => $request->header( 'shop_id' )] )->with( 'shop' )->first();
        return $user;
    }
    /**
     * Profile Details update
     * @param Request $request
     * @return JsonResponse
     */
    function profileUpdate( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'  => 'required',
                'image' => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            $user = User::where( ['id' => $request->header( 'id' ), 'email' => $request->header( 'email' )] )->first();

            $imageUrl = $user->image ?? null;
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                if ( $imageUrl && file_exists( public_path( $imageUrl ) ) ) {
                    // File::delete( $imageUrl );
                    unlink( public_path( $imageUrl ) );
                }
                $imageUrl = 'upload/profile/' . hexdec( uniqid() ) . '.' . $image->getClientOriginalExtension();

                $image->move( public_path( 'upload/profile' ), $imageUrl );
            }
            $user->update( [
                'name'   => $request->name,
                'mobile' => $request->mobile,
                'image'  => $imageUrl,
            ] );
            return response()->json( ['status' => 'success', 'message' => 'Profile Update Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Change Password view
     * @return View
     */
    function changePassword(): View {
        return view( 'profile.change_password' );
    }
    /**
     * Profile Image Update
     * @param Request $request
     * @return JsonResponse
     */
    function updatePassword( Request $request ): JsonResponse {
        $user = User::where( ['id' => $request->header( 'id' ), 'shop_id' => $request->header( 'shop_id' )] )->first();

        if ( Hash::check( $request->old_password, $user->password ) ) {
            $user->update( ['password' => Hash::make( $request->new_password )] );
            return response()->json( ['status' => 'success', 'message' => 'Password Updated Successfully'], 200 );
        } else {
            return response()->json( ['status' => 'failed', 'message' => 'OLD Password Not Match'], 200 );
        }
    }
}