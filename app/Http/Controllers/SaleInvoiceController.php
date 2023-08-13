<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleInvoiceController extends Controller {
    /**
     * Sales Invoice Create
     * @param Request $request
     * @return JsonResponse
     */
    public function createInvoice( Request $request ): JsonResponse {
        try {
            DB::beginTransaction();
            $invoice = SaleInvoice::create( array_merge(
                ['user_id' => $request->header( 'id' ), 'shop_id' => $request->header( 'shop_id' )],
                $request->only( 'customer_id', 'total', 'discount', 'tax' )
            ) );

            foreach ( $request->products as $product ) {
                Sale::create( [
                    'sale_invoice_id' => $invoice->id,
                    'user_id'         => $request->header( 'id' ),
                    'shop_id'         => $request->header( 'shop_id' ),
                    'product_id'      => $product['product_id'],
                    'qty'             => $product['qty'],
                    'price'           => $product['price'],
                ] );
            }
            DB::commit();
            return response()->json( ['status' => 'success', 'message' => 'Invoice Create Successfully'], 201 );
        } catch ( \Throwable $th ) {
            DB::rollBack();
            return response()->json( ['status' => 'failed', 'message' => 'Invoice Create Failed'], 200 );
        }
    }

    /**
     * Single Invoice
     * @param Request $request
     * @return object
     */
    public function index( Request $request ): object {
        return SaleInvoice::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->with( 'customer' )->first();
    }

    /**
     * Invoice Details
     * @param Request $request
     * @return array
     */
    public function invoiceDetails( Request $request ): array{
        // return SaleInvoice::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->with( ['customer', 'sale_products'] )->first();

        $result = SaleInvoice::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->with( ['customer', 'sale_products'] )->first();
        $products = [];
        foreach ( $result->sale_products as $product ) {
            $products[] = ['qty' => $product->qty, 'price' => $product->price];
        }
        return [
            'invoice'  => [
                'id'       => $result->id,
                'discount' => $result->discount,
                'tax'      => $result->tax,
                'total'    => $result->total,
            ],
            'customer' => [
                'name'  => $result->customer->name,
                'email' => $result->customer->email,
            ],
            'products' => $products,
        ];

    }
}
