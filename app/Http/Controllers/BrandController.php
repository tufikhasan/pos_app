<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller {
    /**
     * Retrives All Brand
     * @param Request
     * @return Collection
     */
    public function getBrands( Request $request ): Collection {
        return Brand::where( 'user_id', $request->header( 'id' ) )->latest()->get();
    }

    /**
     * Add new Brand
     * @param Request
     * @return JsonResponse
     */
    public function addBrand( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'  => 'required',
                'image' => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            $imageUrl = null;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                $imageUrl = 'user_' . $request->header( 'id' ) . '_' . date( 'Y_m_d_H_i_s_a' ) . '.' . $image->getClientOriginalExtension();
                $image->storeAs( 'public/brand', $imageUrl );
            }
            //add new Brand
            Brand::create( [
                'user_id' => $request->header( 'id' ),
                'name'    => $request->name,
                'image'   => $imageUrl,
            ] );
            return response()->json( ['status' => 'success', 'message' => 'Brand Created Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Retrive Single Brand
     * @param Request
     * @return Brand|null
     */
    public function singeBrand( Request $request ): ?Brand {
        return Brand::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
    }

    /**
     * Update Brand
     * @param Request
     * @return JsonResponse
     */
    public function updateBrand( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'  => 'required',
                'image' => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 200 );
            }

            $brand = Brand::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
            $imageUrl = $brand->image;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                //delete existing image
                if ( $imageUrl ) {
                    if ( Storage::fileExists( 'public/brand/' . $imageUrl ) ) {
                        Storage::delete( 'public/brand/' . $imageUrl );
                    }
                }
                //upload new image
                $image = $request->file( 'image' );
                $imageUrl = 'user_' . $request->header( 'id' ) . '_' . date( 'Y_m_d_H_i_s_a' ) . '.' . $image->getClientOriginalExtension();
                $image->storeAs( 'public/brand', $imageUrl );
            }
            //Brand updated
            $brand->update( [
                'name'  => $request->name,
                'image' => $imageUrl,
            ] );
            return response()->json( ['status' => 'success', 'message' => 'Brand Updated Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Delete Brand
     * @param Request
     * @return JsonResponse
     */
    public function deleteBrand( Request $request ): JsonResponse {
        try {
            $brand = Brand::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
            //delete existing image
            if ( $brand->image ) {
                if ( Storage::fileExists( 'public/brand/' . $brand->image ) ) {
                    Storage::delete( 'public/brand/' . $brand->image );
                }
            }
            //delete Brand
            if ( $brand->delete() ) {
                return response()->json( ['status' => 'success', 'message' => 'Brand Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Brand Not Found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function brandPage(): View {
        return view( 'pages.brand.brand_list' );
    }
}
