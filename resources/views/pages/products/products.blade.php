@php
    $user_id = request()->header('id');
    $user_role = request()->header('role');
@endphp
@extends('layouts.backend')
@section('site_title', 'products Management')
@section('content')
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">product Management</h3>
                    <p class="text-sm mb-0">
                        This is an exmaple of datatable using the well known datatables.
                    </p>
                </div>
                <div class="card-body">
                    <!-- Button trigger modal -->
                    <div class="text-right">
                        <button class="btn btn-icon btn-primary" type="button" data-toggle="modal"
                            data-target="#add_product_modal">
                            <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                            <span class="btn-inner--text">Add New product</span>
                        </button>
                    </div>

                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th>SL NO:</th>
                                    <th>SKU</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Unit</th>
                                    <th>Stock</th>
                                    <th>Created By</th>
                                    @if (in_array($user_role, ['admin', 'manager']))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="products_list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.products.add')
    @include('pages.products.edit')
    @include('pages.products.delete')
@endsection
@section('script')
    @include('pages.products.script')
@endsection
