<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffsController extends Controller {
    /**
     * Profile Page view
     * @return View
     */
    function staffsPage(): View {
        return view( 'pages.staffs.staffs' );
    }

    /**
     * Retrive All staffs
     * @param Request $request
     * @return Collection
     */
    function allStaffs( Request $request ): Collection {
        return User::where( 'shop_id', $request->header( 'shop_id' ) )->latest()->get();
    }

    /**
     * Retrive All staffs
     * @param Request $request
     * @return object
     */
    function singleStaff( Request $request ): object {
        return User::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
    }

    /**
     * Add new Staff
     * @param Request $request
     * @return JsonResponse
     */
    function addStaff( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'email'  => 'required|unique:users,email',
                'mobile' => 'unique:users,mobile',
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            $user = User::create( array_merge( $request->only( 'name', 'email', 'mobile', 'role' ), ['shop_id' => $request->header( 'shop_id' ), 'password' => Hash::make( $request->password )] ) );

            if ( $user ) {
                return response()->json( ['status' => 'success', 'message' => 'Staff Created Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Failed Staff Create'], 200 );

        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Internal Server Error'], 200 );
        }
    }

    /**
     * Add new Staff
     * @param Request $request
     * @return JsonResponse
     */
    function updateStaff( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'email'  => 'required|unique:users,email,' . $request->id . ',id',
                'mobile' => 'unique:users,mobile,' . $request->id . ',id',
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            $user = User::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();

            if ( $user->update( $request->only( 'name', 'email', 'mobile', 'role' ) ) ) {
                return response()->json( ['status' => 'success', 'message' => 'Staff Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Failed Staff Update'], 200 );

        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Internal Server Error'], 200 );
        }
    }

    /**
     * Delete Staff
     * @param Request $request
     * @return JsonResponse
     */
    function deleteStaff( Request $request ): JsonResponse {
        try {
            $user = User::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            if ( $user ) {
                if ( $user->image ) {
                    unlink( public_path( $user->image ) );
                }
                $user->delete();
                return response()->json( ['status' => 'success', 'message' => 'Staff Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Staff Not Found'], 200 );

        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Internal Server Error'], 200 );
        }
    }
}
