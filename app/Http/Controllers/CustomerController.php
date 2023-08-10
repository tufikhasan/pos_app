<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller {
    /**
     * Retrives All Customers
     * @param Request
     * @return Collection
     */
    public function getCustomers( Request $request ): Collection {
        return Customer::where( 'shop_id', $request->header( 'shop_id' ) )->with( 'user' )->latest()->get();
    }

    /**
     * Add new Customer
     * @param Request
     * @return JsonResponse
     */
    public function addCustomer( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'   => 'required',
                'email'  => 'required|unique:customers,email',
                'mobile' => 'unique:customers,mobile',
                // 'image' => ['image', 'mimes:png,jpg', 'dimensions:min_width=100,min_height=100,max_width=150,max_height=150'],
                'image'  => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $imageUrl = null;
            //check request image file exists or not
            if ( $request->file( 'image' ) ) {
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/customer' ), $imageUrl );
            }
            //add new customer
            $cus = Customer::create( [
                'user_id' => $request->header( 'id' ),
                'shop_id' => $request->header( 'shop_id' ),
                'name'    => $request->name,
                'email'   => $request->email,
                'mobile'  => $request->mobile,
                'image'   => $imageUrl,
            ] );
            if ( $cus ) {
                return response()->json( ['status' => 'success', 'message' => 'Customer Created Successfully'], 201 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Customer Created Failed'], 200 );

        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Retrive Single Customer
     * @param Request
     * @return Customer|null
     */
    public function singeCustomer( Request $request ): ?Customer {
        return Customer::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
    }

    /**
     * Update Customer
     * @param Request
     * @return JsonResponse
     */
    public function updateCustomer( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'name'   => 'required',
                'email'  => 'required|unique:customers,email,' . $request->id . ',id',
                'mobile' => 'unique:customers,mobile,' . $request->id . ',id',
                'image'  => ['image', 'max:512', 'mimes:png,jpg', 'dimensions:between=100,150,100,150'],
            ] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 403 );
            }
            $customer = Customer::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            $imageUrl = $customer->image;
            //check request image file exists or not
            if ( $request->hasFile( 'image' ) ) {
                //delete existing image
                if ( $imageUrl ) {
                    if ( file_exists( public_path( 'upload/customer/' . $imageUrl ) ) ) {
                        unlink( public_path( 'upload/customer/' . $imageUrl ) );
                    }
                }

                //upload new image
                $image = $request->file( 'image' );
                $imageUrl = 'shop_' . $request->header( 'shop_id' ) . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move( public_path( 'upload/customer' ), $imageUrl );
            }
            //customer updated
            $update = $customer->update( [
                'name'   => $request->name,
                'email'  => $request->email,
                'mobile' => $request->mobile,
                'image'  => $imageUrl,
            ] );
            if ( $update ) {
                return response()->json( ['status' => 'success', 'message' => 'Customer Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Customer Updated Failed'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 400 );
        }
    }

    /**
     * Delete Customer
     * @param Request
     * @return JsonResponse
     */
    public function deleteCustomer( Request $request ): JsonResponse {
        try {
            $customer = Customer::where( ['id' => $request->id, 'shop_id' => $request->header( 'shop_id' )] )->first();
            if ( $customer ) {
                //delete existing image
                if ( $customer->image ) {
                    if ( $customer->image ) {
                        if ( file_exists( public_path( 'upload/customer/' . $customer->image ) ) ) {
                            unlink( public_path( 'upload/customer/' . $customer->image ) );
                        }
                    }
                }
                //delete customer
                $customer->delete();
                return response()->json( ['status' => 'success', 'message' => 'Customer Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Customer Not Found'], 200 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went wrong'], 200 );
        }
    }

    /**
     * Customer list page
     * @return View
     */
    public function customerPage(): View {
        return view( 'pages.customers.customers' );
    }
}
