<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller {
    /**
     * Retrives All Expense
     * @param Request
     * @return Collection
     */
    public function getExpenses( Request $request ): Collection {
        return Expense::where( 'shop_id', $request->header( 'shop_id' ) )->with( ['user', 'expense_category'] )->latest()->get();
    }

    /**
     * Add new Expense
     * @param Request
     * @return JsonResponse
     */
    public function addExpense( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), ['amount' => 'required'] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            //add new Expense
            $expense = Expense::create( [
                'user_id'             => $request->header( 'id' ),
                'shop_id'             => $request->header( 'shop_id' ),
                'expense_category_id' => $request->expense_category_id,
                'amount'              => $request->amount,
                'description'         => $request->description,
            ] );
            if ( $expense ) {
                return response()->json( ['status' => 'success', 'message' => 'Expense Created Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Expense Create failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Retrive Single Expense
     * @param Request
     * @return Expense|null
     */
    public function singeExpense( Request $request ): ?Expense {
        return Expense::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
    }

    /**
     * Update Expense
     * @param Request
     * @return JsonResponse
     */
    public function updateExpense( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), ['amount' => 'required'] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $expense = Expense::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->update( [
                'expense_category_id' => $request->expense_category_id,
                'amount'              => $request->amount,
                'description'         => $request->description,
            ] );

            //Expense updated
            if ( $expense ) {
                return response()->json( ['status' => 'success', 'message' => 'Expense Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Expense Updated Failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Delete Expense
     * @param Request
     * @return JsonResponse
     */
    public function deleteExpense( Request $request ): JsonResponse {
        try {
            $expense = Expense::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->delete();
            //delete Expense
            if ( $expense ) {
                return response()->json( ['status' => 'success', 'message' => 'Expense Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Expense Not Found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 200 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function expensePage(): View {
        return view( 'pages.expense.expenses' );
    }
}
