<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {
    /**
     * Retrives All Category
     * @param Request
     * @return Collection
     */
    public function getCategories( Request $request ): Collection {
        return Category::where( 'shop_id', $request->header( 'shop_id' ) )->with( 'user' )->latest()->get();
    }

    /**
     * Add new Category
     * @param Request
     * @return JsonResponse
     */
    public function addCategory( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'  => 'required',
                'image' => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $count = Category::where( ['shop_id' => $request->header( 'shop_id' ), 'name' => $request->name] )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Category Already Exists'], 200 );
            }
            $imageUrl = null;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/category' ), $imageUrl );
            }
            //add new category
            $category = Category::create( [
                'user_id' => $request->header( 'id' ),
                'shop_id' => $request->header( 'shop_id' ),
                'name'    => $request->name,
                'image'   => $imageUrl,
            ] );
            if ( $category ) {
                return response()->json( ['status' => 'success', 'message' => 'Category Created Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Category Create failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Retrive Single Category
     * @param Request
     * @return Category|null
     */
    public function singeCategory( Request $request ): ?Category {
        return Category::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
    }

    /**
     * Update Category
     * @param Request
     * @return JsonResponse
     */
    public function updateCategory( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'  => 'required',
                'image' => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $count = Category::where( ['shop_id' => $request->header( 'shop_id' ), 'name' => $request->name] )->whereNot( 'id', $request->id )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Category Already Exists'], 200 );
            }
            $category = Category::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            $imageUrl = $category->image;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                //delete existing image
                if ( $imageUrl ) {
                    if ( file_exists( public_path( 'upload/category/' . $imageUrl ) ) ) {
                        unlink( public_path( 'upload/category/' . $imageUrl ) );
                    }
                }
                //upload new image
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/category' ), $imageUrl );
            }
            //category updated
            $result = $category->update( [
                'name'  => $request->name,
                'image' => $imageUrl,
            ] );
            if ( $result ) {
                return response()->json( ['status' => 'success', 'message' => 'Category Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Category Updated Failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Delete Category
     * @param Request
     * @return JsonResponse
     */
    public function deleteCategory( Request $request ): JsonResponse {
        try {
            $category = Category::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            //delete existing image
            if ( $category->image ) {
                if ( file_exists( public_path( 'upload/category/' . $category->image ) ) ) {
                    unlink( public_path( 'upload/category/' . $category->image ) );
                }
            }
            //delete category
            if ( $category->delete() ) {
                return response()->json( ['status' => 'success', 'message' => 'Category Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Category Not Found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 200 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function categoryPage(): View {
        return view( 'pages.category.category' );
    }
}
