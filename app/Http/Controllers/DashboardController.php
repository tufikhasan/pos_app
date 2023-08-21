<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\SaleInvoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function dashboardView( Request $request ): View {
        /* 01: long process */
        // $total_sales = SaleInvoice::where( 'shop_id', $request->header( 'shop_id' ) )->sum( 'sub_total' );
        // $total_tax = SaleInvoice::where( 'shop_id', $request->header( 'shop_id' ) )->sum( 'tax' );
        // $total_qty = SaleInvoice::where( 'shop_id', $request->header( 'shop_id' ) )->sum( 'total_qty' );

        /* 02: short query */
        //lifetime sale sum
        $totalSumData = SaleInvoice::where( 'shop_id', $request->header( 'shop_id' ) )
            ->selectRaw( 'SUM(sub_total) as total_sales, SUM(tax) as total_tax, SUM(total_qty) as total_qty' )
            ->first();

        //month sale sum
        $monthlySumData = SaleInvoice::where( 'shop_id', $request->header( 'shop_id' ) )
            ->whereMonth( 'created_at', Carbon::now()->month )
            ->whereYear( 'created_at', Carbon::now()->year )
            ->selectRaw( 'SUM(sub_total) as total_sales, SUM(tax) as total_tax, SUM(total_qty) as total_qty' )
            ->first();

        //today sale sum
        $todaySumData = SaleInvoice::where( 'shop_id', $request->header( 'shop_id' ) )
            ->whereDate( 'created_at', Carbon::now()->format( 'Y-m-d' ) )
            ->selectRaw( 'SUM(sub_total) as total_sales, SUM(tax) as total_tax, SUM(total_qty) as total_qty' )
            ->first();

        //Expenses sum
        $totalExpenses = Expense::where( 'shop_id', $request->header( 'shop_id' ) )->sum( 'amount' );
        $monthlyExpenses = Expense::where( 'shop_id', $request->header( 'shop_id' ) )->whereMonth( 'created_at', Carbon::now()->month )->whereYear( 'created_at', Carbon::now()->year )->sum( 'amount' );
        $todayExpenses = Expense::where( 'shop_id', $request->header( 'shop_id' ) )->whereDate( 'created_at', Carbon::now()->format( 'Y-m-d' ) )->sum( 'amount' );
        $expenses = [
            'totalExpenses'   => $totalExpenses,
            'monthlyExpenses' => $monthlyExpenses,
            'todayExpenses'   => $todayExpenses,
        ];

        $customer = Customer::where( 'shop_id', $request->header( 'shop_id' ) )->count();
        $staff = User::where( 'shop_id', $request->header( 'shop_id' ) )->whereNot( 'role', 'admin' )->count();
        $category = Category::where( 'shop_id', $request->header( 'shop_id' ) )->count();
        $product = Product::where( 'shop_id', $request->header( 'shop_id' ) )->count();

        return view( 'dashboard.dashboard', compact( 'totalSumData', 'todaySumData', 'monthlySumData', 'expenses', 'customer', 'staff', 'category', 'product' ) );
    }
}
