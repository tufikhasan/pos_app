@extends('layouts.frontend')
@section('site_title', 'Sign Up - Inventory')
@section('content')
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-0">
        <div class="container">
            <div class="header-body text-center">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <h1 class="text-white">Create an account</h1>
                        <p class="text-lead text-white">Use these awesome forms to login or create new account in
                            your project for free.</p>
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
                            <small>Sign up with credentials</small>
                        </div>
                        <form id="signup_form">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-shop"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Shop Name" type="text" id="shop_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Your Name" type="text" id="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Email" type="email" id="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-mobile-button"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Mobile" type="tel" id="mobile">
                                </div>
                            </div>
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
                            <div class="text-muted font-italic"><small>Already Have an account: <a
                                        href="{{ route('signin.page') }}" class="text-success font-weight-700">Sign
                                        In</a></small></div>
                            <div class="row my-4">
                                <div class="col-12">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" id="customCheckSignup" type="checkbox">
                                        <label class="custom-control-label" for="customCheckSignup">
                                            <span class="text-muted">I agree with the <a href="#!">Privacy
                                                    Policy</a></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">Create account</button>
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
        const form = document.getElementById("signup_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                const shop_name = document.getElementById("shop_name").value;
                const name = document.getElementById("name").value;
                const email = document.getElementById("email").value;
                const mobile = document.getElementById("mobile").value;
                const password = document.getElementById("password").value;
                const confirm_password = document.getElementById("confirm_password").value;
                const customCheckSignup = document.getElementById("customCheckSignup");
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const regexMobileNumber = /^(\+8801|01)\d{9}$/;

                if (0 == shop_name.length) {
                    toastr.info("Shop Name is Required");
                } else if (0 == name.length) {
                    toastr.info("Your Name is Required");
                } else if (0 == email.length) {
                    toastr.info("Email is Required");
                } else if (!emailRegex.test(email)) {
                    toastr.info("Invalid email format");
                } else if (0 == mobile.length) {
                    toastr.info("Mobile Number is Required");
                } else if (!regexMobileNumber.test(mobile)) {
                    toastr.info("Invalid Mobile format");
                } else if (0 == password.length) {
                    toastr.info("Password is Required");
                } else if (0 == confirm_password.length) {
                    toastr.info("Confirm Password is Required");
                } else if (confirm_password !== password) {
                    toastr.info("Password & Confirm Password Should Be Same");
                } else if (!customCheckSignup.checked) {
                    toastr.info("Are you agree with the Privacy Policy?");
                } else {
                    showLoader()
                    const data = {
                        shop_name: shop_name,
                        name: name,
                        email: email,
                        mobile: mobile,
                        password: password,
                    };
                    const URL = "{{ route('signup') }}";
                    const response = await axios.post(URL, data);
                    if (
                        201 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message);
                        window.location.href = "{{ route('signin.page') }}";
                    } else if (
                        200 == response.status && response.data.message.email
                    ) {
                        toastr.error(response.data.message.email);
                    } else if (
                        200 == response.status && response.data.message.mobile
                    ) {
                        toastr.error(response.data.message.mobile);
                    }
                }
                hideLoader()
            } catch (error) {
                if (400 == error.response.status) {
                    toastr.error(error.response.data.message.email);
                }
                hideLoader()
            }
        });
    </script>
@endsection
