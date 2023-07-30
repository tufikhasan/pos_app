<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {
    /**
     * Retrives All Product
     * @param Request
     * @return Collection
     */
    public function getProducts( Request $request ): Collection {
        return Product::where( 'user_id', $request->header( 'id' ) )->latest()->get();
    }

    /**
     * Add new Product
     * @param Request
     * @return JsonResponse
     */
    public function addProduct( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'        => 'required',
                'price'       => 'required',
                'unit'        => 'required',
                'brand_id'    => 'required',
                'category_id' => 'required',
                'image'       => ['image', 'max:1024', 'mimes:png,jpg', 'dimensions:between=300,350,300,350'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            $imageUrl = null;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                $imageUrl = 'user_' . $request->header( 'id' ) . '_' . date( 'Y_m_d_H_i_s_a' ) . '.' . $image->getClientOriginalExtension();
                $image->storeAs( 'public/product', $imageUrl );
            }
            //add new Product
            Product::create( [
                'user_id'     => $request->header( 'id' ),
                'name'        => $request->name,
                'price'       => $request->price,
                'unit'        => $request->unit,
                'brand_id'    => $request->brand_id,
                'category_id' => $request->category_id,
                'image'       => $imageUrl,
            ] );
            return response()->json( ['status' => 'success', 'message' => 'Product Created Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Retrive Single Product
     * @param Request
     * @return Product|null
     */
    public function singeProduct( Request $request ): ?Product {
        return Product::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
    }

    /**
     * Update Product
     * @param Request
     * @return JsonResponse
     */
    public function updateProduct( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'        => 'required',
                'price'       => 'required',
                'unit'        => 'required',
                'brand_id'    => 'required',
                'category_id' => 'required',
                'image'       => ['image', 'max:1024', 'mimes:png,jpg', 'dimensions:between=300,350,300,350'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 200 );
            }

            $product = Product::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
            $imageUrl = $product->image;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                //delete existing image
                if ( $imageUrl ) {
                    if ( Storage::fileExists( 'public/product/' . $imageUrl ) ) {
                        Storage::delete( 'public/product/' . $imageUrl );
                    }
                }
                //upload new image
                $image = $request->file( 'image' );
                $imageUrl = 'user_' . $request->header( 'id' ) . '_' . date( 'Y_m_d_H_i_s_a' ) . '.' . $image->getClientOriginalExtension();
                $image->storeAs( 'public/product', $imageUrl );
            }
            //Product updated
            $product->update( [
                'name'        => $request->name,
                'price'       => $request->price,
                'unit'        => $request->unit,
                'brand_id'    => $request->brand_id,
                'category_id' => $request->category_id,
                'image'       => $imageUrl,
            ] );
            return response()->json( ['status' => 'success', 'message' => 'Product Updated Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Delete Product
     * @param Request
     * @return JsonResponse
     */
    public function deleteProduct( Request $request ): JsonResponse {
        try {
            $product = Product::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
            //delete existing image
            if ( $product->image ) {
                if ( Storage::fileExists( 'public/product/' . $product->image ) ) {
                    Storage::delete( 'public/product/' . $product->image );
                }
            }
            //delete Product
            if ( $product->delete() ) {
                return response()->json( ['status' => 'success', 'message' => 'Product Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Product Not Found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function ProductPage(): View {
        return view( 'pages.product.product_list' );
    }
}
