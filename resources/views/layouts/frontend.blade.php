<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Tailwind Admin Template">
    <meta name="keywords"
        content="admin, estimates, tailwind, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Tailwind Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('site_title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/flowbite.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dist/tailwind-dist.css') }}">
    <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}">
</head>

<body class="account-page">
    <div id="loader" class="LoadingOverlay">
        <div class="Line-Progress">
            <div class="indeterminate"></div>
        </div>
    </div>
    <div class="main-wrapper">
        @yield('content')
    </div>


    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/flowbite.js') }}"></script>
    <script src="{{ asset('assets/js/alpine.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/flowbite.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>
    @yield('script')
</body>

</html>
