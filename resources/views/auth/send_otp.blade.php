@extends('layouts.frontend')
@section('site_title', 'Forgot Password - Inventory')
@section('content')
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-0">
        <div class="container">
            <div class="header-body text-center">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <h1 class="text-white">Forgot password</h1>
                        <p class="text-lead text-white">Please enter your account email address to receive an OTP (One-Time
                            Password) in your email for
                            changing your password.</p>
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
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary border-0 mb-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>Send Otp Your Email</small>
                        </div>
                        <form id="otp_form">
                            <div class="form-group mb-3">
                                <div class="input-group input-group-merge input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Email" type="text" id="email">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">OTP Send</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <a href="{{ route('signin.page') }}" class="text-success font-weight-700"><small>
                                << Back</small></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const form = document.getElementById("otp_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                showLoader()
                const email = document.getElementById("email").value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (0 == email.length) {
                    toastr.info("Email is Required");
                } else if (!emailRegex.test(email)) {
                    toastr.info("Invalid email format");
                } else {
                    const data = {
                        email: email,
                    };
                    const URL = "{{ route('send.otp') }}";
                    const response = await axios.post(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message);
                        sessionStorage.setItem('email', email);
                        window.location.href = "{{ route('verifyOtp.page') }}";
                    }
                }
                hideLoader()
            } catch (error) {
                if (401 == error.response.status) {
                    toastr.error(error.response.data.message);
                }
                hideLoader()
            }
        });
    </script>
@endsection
