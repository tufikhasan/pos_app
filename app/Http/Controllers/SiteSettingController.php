<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSettingController extends Controller {
    /**
     * Promotion mail page
     * @return View
     */
    public function shopSettingPage( Request $request ): View {
        $shop = Shop::where( 'id', $request->header( 'shop_id' ) )->first();
        return view( 'pages.shop.shop_setting', compact( 'shop' ) );
    }

    public function shopUpdate( Request $request ) {
        try {
            $validator = Validator::make( $request->all(), [
                'shop_name' => 'required',
                'logo'      => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }

            $shop = Shop::where( 'id', $request->header( 'shop_id' ) )->first();
            $logoUrl = $shop->logo;
            //check request image file exists or not
            if ( $request->hasFile( 'logo' ) ) {
                //delete existing image
                if ( $logoUrl ) {
                    if ( file_exists( public_path( 'upload/shop/' . $logoUrl ) ) ) {
                        unlink( public_path( 'upload/shop/' . $logoUrl ) );
                    }
                }
                //upload new image
                $logo = $request->file( 'logo' );
                $logoUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $logo->getClientOriginalExtension();
                $logo->move( public_path( 'upload/shop' ), $logoUrl );
            }
            //Brand updated
            $result = $shop->update( [
                'shop_name' => $request->shop_name,
                'logo'      => $logoUrl,
            ] );
            if ( $result ) {
                return response()->json( ['status' => 'success', 'message' => 'Shop Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Shop Updated Failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }
}
