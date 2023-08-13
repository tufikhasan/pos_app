<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller {
    /**
     * Retrives All Category
     * @param Request
     * @return Collection
     */
    public function getCategories( Request $request ): Collection {
        return ExpenseCategory::where( 'shop_id', $request->header( 'shop_id' ) )->with( 'user' )->latest()->get();
    }

    /**
     * Add new Category
     * @param Request
     * @return JsonResponse
     */
    public function addCategory( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), ['name' => 'required'] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }

            $count = ExpenseCategory::where( ['shop_id' => $request->header( 'shop_id' ), 'name' => $request->name] )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Expense Category Already Exists'], 200 );
            }
            //add new category
            $category = ExpenseCategory::create( [
                'user_id' => $request->header( 'id' ),
                'shop_id' => $request->header( 'shop_id' ),
                'name'    => $request->name,
            ] );
            if ( $category ) {
                return response()->json( ['status' => 'success', 'message' => 'Expense Category Created Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Expense Category Create failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Retrive Single Category
     * @param Request
     * @return ExpenseCategory|null
     */
    public function singeCategory( Request $request ): ?ExpenseCategory {
        return ExpenseCategory::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
    }

    /**
     * Update Category
     * @param Request
     * @return JsonResponse
     */
    public function updateCategory( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), ['name' => 'required'] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $count = ExpenseCategory::where( ['shop_id' => $request->header( 'shop_id' ), 'name' => $request->name] )->whereNot( 'id', $request->id )->first();
            if ( $count ) {
                return response()->json( ['status' => 'failed', 'message' => 'Expense Category Already Exists'], 200 );
            }
            $category = ExpenseCategory::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();

            //category updated
            if ( $category->update( $request->only( 'name' ) ) ) {
                return response()->json( ['status' => 'success', 'message' => 'Expense Category Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Expense Category Updated Failed'], 200 );
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
            $category = ExpenseCategory::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->delete();
            //delete category
            if ( $category ) {
                return response()->json( ['status' => 'success', 'message' => 'Expense Category Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Expense Category Not Found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 200 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function categoryPage(): View {
        return view( 'pages.expense.category.category' );
    }
}
