@extends('layouts.backend')
@section('site_title', 'Reports')
@section('content')
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Sales Report</h3>
                    <p class="text-sm mb-0">
                        Download Sales Reports
                    </p>
                </div>
                <div class="card-body">
                    <form id="sales_report_form">
                        <div class="form-group">
                            <label for="fromDate" class="form-control-label">From</label>
                            <input class="form-control" type="date" id="fromDate">
                        </div>
                        <div class="form-group">
                            <label for="toDate" class="form-control-label">To</label>
                            <input class="form-control" type="date" id="toDate">
                        </div>
                        <button class="btn btn-primary" type="submit">Download</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Expenses Report</h3>
                    <p class="text-sm mb-0">
                        Download Expenses Reports
                    </p>
                </div>
                <div class="card-body">
                    <form id="expenses_report_form">
                        <div class="form-group">
                            <label for="expenseFromDate" class="form-control-label">From</label>
                            <input class="form-control" type="date" id="expenseFromDate">
                        </div>
                        <div class="form-group">
                            <label for="expenseToDate" class="form-control-label">To</label>
                            <input class="form-control" type="date" id="expenseToDate">
                        </div>
                        <button class="btn btn-primary" type="submit">Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        const sales_report_form = document.getElementById('sales_report_form');
        sales_report_form.addEventListener('submit', (e) => {
            e.preventDefault();
            try {
                const formDate = document.getElementById('fromDate').value;
                const toDate = document.getElementById('toDate').value;
                if (!formDate) {
                    toastr.info("From Date is required");
                } else if (!toDate) {
                    toastr.info("To Date is required");
                } else {
                    const URL = "{{ route('sale.report', [':formDate', ':toDate']) }}".replace(':formDate',
                        formDate).replace(':toDate', toDate);
                    window.open(URL, '_blank');
                }
            } catch (error) {
                console.log("Something went wrong");
            }
        });

        const expenses_report_form = document.getElementById('expenses_report_form');
        expenses_report_form.addEventListener('submit', (e) => {
            e.preventDefault();
            try {
                const formDate = document.getElementById('expenseFromDate').value;
                const toDate = document.getElementById('expenseToDate').value;
                if (!formDate) {
                    toastr.info("From Date is required");
                } else if (!toDate) {
                    toastr.info("To Date is required");
                } else {
                    const URL = "{{ route('expense.report', [':formDate', ':toDate']) }}".replace(':formDate',
                        formDate).replace(':toDate', toDate);
                    window.open(URL, '_blank');
                }
            } catch (error) {
                console.log("Something went wrong");
            }
        });
    </script>
@endsection
