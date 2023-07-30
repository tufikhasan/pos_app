@extends('layouts.backend')
@section('site_title', 'Customer List')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Customer List</h4>
                <h6>Manage your Customers</h6>
            </div>
            <div class="page-btn">
                <button type="button" onclick="showModal('add_customer_modal')" class="btn btn-added">
                    <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" />Add Customer
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img" />
                                <span><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img" /></span>
                            </a>
                        </div>
                        <div class="search-input">
                            <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                    alt="img" /></a>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                        src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img" /></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                        src="{{ asset('assets/img/icons/excel.svg') }}" alt="img" /></a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                        src="{{ asset('assets/img/icons/printer.svg') }}" alt="img" /></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card" id="filter_inputs">
                    <div class="card-body !pb-0">
                        <div class="row">
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Enter Customer Code" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Enter Customer Name" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Enter Phone Number" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6 col-12">
                                <div class="form-group">
                                    <input type="text" placeholder="Enter Email" />
                                </div>
                            </div>
                            <div class="col-lg-1 col-sm-6 col-12 ml-auto">
                                <div class="form-group">
                                    <a class="btn btn-filters ml-auto"><img
                                            src="{{ asset('assets/img/icons/search-whites.svg') }}" alt="img" /></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>SL NO:</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="customer_list"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('pages.customers.add_customer_modal')
    @include('pages.customers.edit_customer_modal')
    @include('pages.customers.delete_modal')
@endsection

@section('script')
    @include('pages.customers.script')
@endsection
