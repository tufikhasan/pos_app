<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleInvoice;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleInvoiceController extends Controller {
    /**
     * Sale Page
     * @return View
     */
    public function salePage(): View {
        return view( 'pages.sales.sale' );
    }

    /**
     * Add to cart product
     * @param Request $request
     * @return JsonResponse
     */
    function addToCart( Request $request ): JsonResponse {
        try {
            $added = Cart::add( [
                'id'    => $request->id,
                'name'  => $request->name,
                'qty'   => 1,
                'price' => $request->price,
            ] );
            if ( $added ) {
                return response()->json( ['status' => 'success', 'message' => 'Product Addded Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Product Addded failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Product Quantity update
     * @param Request $request
     * @return JsonResponse
     */
    function updateCartQty( Request $request ): JsonResponse {
        try {
            Cart::update( $request->rowId, $request->update_qty );
            return response()->json( ['status' => 'success', 'message' => 'Product Quantity Updated Successfully'], 200 );
        } catch ( \Throwable $th ) {
            // return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
            return response()->json( ['status' => 'failed', 'message' => $th->getMessage()], 400 );
        }
    }

    /**
     * Product Remove From cart
     * @param Request $request
     * @return JsonResponse
     */
    function deleteFromCart( Request $request ): JsonResponse {
        try {
            Cart::remove( $request->rowId );
            return response()->json( ['status' => 'success', 'message' => 'Product Remove From Cart Successfully'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Sales Invoice Create
     * @param Request $request
     * @return JsonResponse
     */
    public function createInvoice( Request $request ): JsonResponse {
        try {
            DB::beginTransaction();

            $invoice = SaleInvoice::create( [
                'user_id'     => $request->header( 'id' ),
                'shop_id'     => $request->header( 'shop_id' ),
                'customer_id' => $request->customer_id,
                'total_qty'   => Cart::count(),
                'sub_total'   => Cart::subtotal(),
                'tax'         => Cart::tax(),
                'total'       => Cart::total(),
            ] );

            foreach ( Cart::content() as $key => $product ) {
                Sale::create( [
                    'sale_invoice_id' => $invoice->id,
                    'user_id'         => $invoice->user_id,
                    'shop_id'         => $invoice->shop_id,
                    'product_id'      => $product->id,
                    'qty'             => $product->qty,
                    'name'            => $product->name,
                    'price'           => $product->price,
                ] );
            }
            DB::commit();
            Cart::destroy();
            return response()->json( ['status' => 'success', 'message' => 'Invoice Create Successfully', 'id' => $invoice->id], 201 );
        } catch ( \Throwable $th ) {
            // DB::rollBack();
            return response()->json( ['status' => 'failed', 'message' => $th->getMessage()], 200 );
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
