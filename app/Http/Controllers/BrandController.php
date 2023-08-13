<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller {
    /**
     * Retrives All Brand
     * @param Request
     * @return Collection
     */
    public function getBrands( Request $request ): Collection {
        return Brand::where( 'shop_id', $request->header( 'shop_id' ) )->with( 'user' )->latest()->get();
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
            ], ['name.unique' => 'Brand Already exists'] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $count = Brand::where( ['shop_id' => $request->header( 'shop_id' ), 'name' => $request->name] )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Brand Already Exists'], 200 );
            }
            $imageUrl = null;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/brand' ), $imageUrl );
            }
            //add new Brand
            $brand = Brand::create( [
                'user_id' => $request->header( 'id' ),
                'shop_id' => $request->header( 'shop_id' ),
                'name'    => $request->name,
                'image'   => $imageUrl,
            ] );
            if ( $brand ) {
                return response()->json( ['status' => 'success', 'message' => 'Brand Created Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Brand Create failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Retrive Single Brand
     * @param Request
     * @return Brand|null
     */
    public function singeBrand( Request $request ): ?Brand {
        return Brand::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
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
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $count = Brand::where( ['shop_id' => $request->header( 'shop_id' ), 'name' => $request->name] )->whereNot( 'id', $request->id )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Brand Already Exists'], 200 );
            }

            $brand = Brand::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            $imageUrl = $brand->image;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                //delete existing image
                if ( $imageUrl ) {
                    if ( file_exists( public_path( 'upload/brand/' . $imageUrl ) ) ) {
                        unlink( public_path( 'upload/brand/' . $imageUrl ) );
                    }
                }
                //upload new image
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/brand' ), $imageUrl );
            }
            //Brand updated
            $result = $brand->update( [
                'name'  => $request->name,
                'image' => $imageUrl,
            ] );
            if ( $result ) {
                return response()->json( ['status' => 'success', 'message' => 'Brand Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Brand Updated Failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Delete Brand
     * @param Request
     * @return JsonResponse
     */
    public function deleteBrand( Request $request ): JsonResponse {
        try {
            $brand = Brand::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            //delete existing image
            if ( $brand->image ) {
                if ( file_exists( public_path( 'upload/brand/' . $brand->image ) ) ) {
                    unlink( public_path( 'upload/brand/' . $brand->image ) );
                }
            }
            //delete Brand
            if ( $brand->delete() ) {
                return response()->json( ['status' => 'success', 'message' => 'Brand Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Brand Not Found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 200 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function brandPage(): View {
        return view( 'pages.brands.brands' );
    }
}
