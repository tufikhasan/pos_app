@extends('layouts.backend')
@section('site_title', 'Reports')
@section('content')
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Reports Sales</h3>
                    <p class="text-sm mb-0">
                        Generate Sales Reports
                    </p>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="fromDate" class="form-control-label">From</label>
                            <input class="form-control" type="date" value="" id="fromDate">
                        </div>
                        <div class="form-group">
                            <label for="toDate" class="form-control-label">To</label>
                            <input class="form-control" type="date" value="" id="toDate">
                        </div>
                        <button class="btn btn-sm btn-primary" type="submit">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')

@endsection
