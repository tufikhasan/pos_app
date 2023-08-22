<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {
    /**
     * Retrives All Product
     * @param Request
     * @return Collection
     */
    public function getProducts( Request $request ): Collection {
        return Product::where( 'shop_id', $request->header( 'shop_id' ) )->with( ['user', 'brand', 'category'] )->latest()->get();
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
                'sku'         => 'required',
                'stock'       => 'required',
                'brand_id'    => 'required',
                'category_id' => 'required',
                'image'       => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=300,350,300,350'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $count = Product::where( ['shop_id' => $request->header( 'shop_id' ), 'sku' => $request->sku] )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Product Already Exists'], 200 );
            }
            $imageUrl = null;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/product' ), $imageUrl );
            }
            //add new Product
            $product = Product::create( [
                'user_id'     => $request->header( 'id' ),
                'shop_id'     => $request->header( 'shop_id' ),
                'sku'         => $request->sku,
                'name'        => $request->name,
                'price'       => $request->price,
                'unit'        => $request->unit,
                'stock'       => $request->stock,
                'brand_id'    => $request->brand_id,
                'category_id' => $request->category_id,
                'image'       => $imageUrl,
            ] );
            if ( $product ) {
                return response()->json( ['status' => 'success', 'message' => 'Product Created Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Product Create failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Retrive Single Product
     * @param Request
     * @return Product|null
     */
    public function singeProduct( Request $request ): ?Product {
        return Product::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
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
                'sku'         => 'required',
                'stock'       => 'required',
                'brand_id'    => 'required',
                'category_id' => 'required',
                'image'       => ['image', 'max:1024', 'mimes:png,jpg', 'dimensions:between=300,350,300,350'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $count = Product::where( ['shop_id' => $request->header( 'shop_id' ), 'sku' => $request->sku] )->whereNot( 'id', $request->id )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Product Already Exists'], 200 );
            }

            $product = Product::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            $imageUrl = $product->image;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                //delete existing image
                if ( $imageUrl ) {
                    if ( file_exists( public_path( 'upload/product/' . $imageUrl ) ) ) {
                        unlink( public_path( 'upload/product/' . $imageUrl ) );
                    }
                }
                //upload new image
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/product' ), $imageUrl );
            }
            //Product updated
            $result = $product->update( [
                'sku'         => $request->sku,
                'name'        => $request->name,
                'price'       => $request->price,
                'unit'        => $request->unit,
                'stock'       => $request->stock,
                'brand_id'    => $request->brand_id,
                'category_id' => $request->category_id,
                'image'       => $imageUrl,
            ] );
            if ( $result ) {
                return response()->json( ['status' => 'success', 'message' => 'Product Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Product Updated Failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Delete Product
     * @param Request
     * @return JsonResponse
     */
    public function deleteProduct( Request $request ): JsonResponse {
        try {
            $product = Product::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            //delete existing image
            if ( $product->image ) {
                if ( file_exists( public_path( 'upload/product/' . $product->image ) ) ) {
                    unlink( public_path( 'upload/product/' . $product->image ) );
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
        return view( 'pages.products.products' );
    }
}
