@extends('layouts.frontend')
@section('site_title', 'Sign Up - Inventory')
@section('content')
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-0">
        <div class="container">
            <div class="header-body text-center">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <h1 class="text-white">Reset Password</h1>
                        <p class="text-lead text-white">Reset your password</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-secondary border-0 mb-2">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>Reset Password</small>
                        </div>
                        <form id="reset_password_form">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Password" type="password" id="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Confirm Password" type="password"
                                        id="confirm_password">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const form = document.getElementById("reset_password_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                const password = document.getElementById("password").value;
                const confirm_password = document.getElementById("confirm_password").value;

                if (0 == password.length) {
                    toastr.info("Password is Required");
                } else if (0 == confirm_password.length) {
                    toastr.info("Confirm password is Required");
                } else if (password != confirm_password) {
                    toastr.info("Password and Confirm must be same");
                } else {
                    showLoader()
                    const data = {
                        password: password,
                        confirm_password: confirm_password,
                    };
                    const URL = "{{ route('reset.password') }}";
                    const response = await axios.post(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message);
                        window.location.href = "{{ route('signin.page') }}";
                    }
                    console.log(response)
                }
                hideLoader()
            } catch (error) {
                if (500 == error.response.status) {
                    toastr.error(error.response.data.message);
                }
                if (400 == error.response.status) {
                    toastr.error(error.response.data.message);
                }
                hideLoader()
            }
        });
    </script>
@endsection
