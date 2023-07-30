<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {
    /**
     * Retrives All Category
     * @param Request
     * @return Collection
     */
    public function getCategories( Request $request ): Collection {
        return Category::where( 'user_id', $request->header( 'id' ) )->get();
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
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            $imageUrl = null;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                $image = $request->file( 'image' );
                $imageUrl = 'user_' . $request->header( 'id' ) . '_' . date( 'Y_m_d_H_i_s_a' ) . '.' . $image->getClientOriginalExtension();
                $image->storeAs( 'public/category', $imageUrl );
            }
            //add new category
            Category::create( [
                'user_id' => $request->header( 'id' ),
                'name'    => $request->name,
                'image'   => $imageUrl,
            ] );
            return response()->json( ['status' => 'success', 'message' => 'Category Created Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Retrive Single Category
     * @param Request
     * @return Category|null
     */
    public function singeCategory( Request $request ): ?Category {
        return Category::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
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
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 200 );
            }

            $category = Category::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
            $imageUrl = $category->image;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                //delete existing image
                if ( $imageUrl ) {
                    if ( Storage::fileExists( 'public/category/' . $imageUrl ) ) {
                        Storage::delete( 'public/category/' . $imageUrl );
                    }
                }
                //upload new image
                $image = $request->file( 'image' );
                $imageUrl = 'user_' . $request->header( 'id' ) . '_' . date( 'Y_m_d_H_i_s_a' ) . '.' . $image->getClientOriginalExtension();
                $image->storeAs( 'public/category', $imageUrl );
            }
            //category updated
            $category->update( [
                'name'  => $request->name,
                'image' => $imageUrl,
            ] );
            return response()->json( ['status' => 'success', 'message' => 'Category Updated Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Delete Category
     * @param Request
     * @return JsonResponse
     */
    public function deleteCategory( Request $request ): JsonResponse {
        try {
            $category = Category::where( ['id' => $request->id, 'user_id' => $request->header( 'id' )] )->first();
            //delete existing image
            if ( $category->image ) {
                if ( Storage::fileExists( 'public/category/' . $category->image ) ) {
                    Storage::delete( 'public/category/' . $category->image );
                }
            }
            //delete category
            if ( $category->delete() ) {
                return response()->json( ['status' => 'success', 'message' => 'Category Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Category Not Found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 500 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function categoryPage(): View {
        return view( 'pages.category.category_list' );
    }
}
