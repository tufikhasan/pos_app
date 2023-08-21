<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller {
    /**
     * Promotion mail page
     * @return View
     */
    public function getReport(): View {
        return view( 'pages.report.report' );
    }

    function salesReport( Request $request ) {
        $shop_id = $request->header( 'shop_id' );
        $fromDate = date( 'Y-m-d', strtotime( $request->fromDate ) );
        $toDate = date( 'Y-m-d', strtotime( $request->toDate ) );

        $totalSum = SaleInvoice::where( 'shop_id', $shop_id )
            ->whereDate( 'created_at', '>=', $fromDate )
            ->whereDate( 'created_at', '<=', $toDate )
            ->selectRaw( 'SUM(sub_total) as total_sales, SUM(tax) as total_tax,SUM(total) as total, SUM(total_qty) as total_qty' )
            ->first();

        $list = SaleInvoice::where( 'shop_id', $shop_id )
            ->whereDate( 'created_at', '>=', $fromDate )
            ->whereDate( 'created_at', '<=', $toDate )
            ->with( 'customer' )
            ->get();

        $sale_products = Sale::where( 'shop_id', $shop_id )
            ->whereDate( 'created_at', '>=', $fromDate )
            ->whereDate( 'created_at', '<=', $toDate )
            ->get();

        $data = ['fromDate' => $fromDate, 'toDate' => $toDate, 'totalSum' => $totalSum, 'list' => $list, 'sale_products' => $sale_products];
        $pdf = Pdf::loadView( 'pages.report.SalesReport', $data );
        return $pdf->download( 'sales_report_' . time() . '.pdf' );
    }
    function expensesReport( Request $request ) {
        $fromDate = date( 'Y-m-d', strtotime( $request->fromDate ) );
        $toDate = date( 'Y-m-d', strtotime( $request->toDate ) );

        $sum = Expense::where( 'shop_id', $request->header( 'shop_id' ) )
            ->whereDate( 'created_at', '>=', $fromDate )
            ->whereDate( 'created_at', '<=', $toDate )
            ->selectRaw( 'SUM(amount) as total_amount, COUNT(id) as qty' )
            ->first();

        $list = Expense::where( 'shop_id', $request->header( 'shop_id' ) )
            ->whereDate( 'created_at', '>=', $fromDate )
            ->whereDate( 'created_at', '<=', $toDate )
            ->with( 'expense_category' )
            ->get();

        $data = ['fromDate' => $fromDate, 'toDate' => $toDate, 'sum' => $sum, 'list' => $list];
        $pdf = Pdf::loadView( 'pages.report.expensesReport', $data );
        return $pdf->download( 'expenses_report_' . time() . '.pdf' );
    }
}
