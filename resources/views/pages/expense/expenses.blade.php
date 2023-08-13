@php
    $user_id = request()->header('id');
    $user_role = request()->header('role');
@endphp
@extends('layouts.backend')
@section('site_title', 'Expense Management')
@section('content')
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Expense Management</h3>
                    <p class="text-sm mb-0">
                        This is an exmaple of datatable using the well known datatables.
                    </p>
                </div>
                <div class="card-body">
                    @if ($user_role != 'seller')
                        <!-- Button trigger modal -->
                        <div class="text-right">
                            <button class="btn btn-icon btn-primary" type="button" data-toggle="modal"
                                data-target="#add_expense_modal">
                                <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                                <span class="btn-inner--text">Add New Expense</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th>SL NO:</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Created By</th>
                                    @if (in_array($user_role, ['admin', 'manager']))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="expense_list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pages.expense.add')
    @include('pages.expense.edit')
    @include('pages.expense.delete')
@endsection
@section('script')
    @include('pages.expense.script')
@endsection
