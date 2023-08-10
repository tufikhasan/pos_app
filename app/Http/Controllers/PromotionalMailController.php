<?php

namespace App\Http\Controllers;

use App\Mail\PromotionalMail;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PromotionalMailController extends Controller {
    /**
     * Promotion mail page
     * @return View
     */
    public function promotionPage(): View {
        return view( 'pages.promotion.promotion' );
    }

    /**
     * Promotion mail send
     * @param Request $request
     * @return JsonResponse
     */
    public function sendPromotionMail( Request $request ): JsonResponse {
        try {
            $customers = Customer::where( 'shop_id', $request->header( 'shop_id' ) )->select( 'email' )->get();
            if ( count( $customers ) != 0 ) {
                foreach ( $customers as $customer ) {
                    Mail::to( $customer->email )->send( new PromotionalMail( $request->subject, $request->message ) );
                }
                return response()->json( ['status' => 'success', 'message' => 'message send successfully', 'customer' => $customers], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'No Customers Found'], 200 );

        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'message send failed'], 200 );
        }
    }
}