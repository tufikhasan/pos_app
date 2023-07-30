@extends('layouts.frontend')
@section('site_title', 'Login - Pos Dashboard')
@section('content')
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <a href="{{ route('register') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="img" /></a>
                    </div>
                    <div class="login-userheading">
                        <h3>Sign In</h3>
                        <h4>Please login to your account</h4>
                    </div>
                    <form id="login_form">
                        <div class="form-login">
                            <label>Email</label>
                            <div class="form-addons">
                                <input type="text" placeholder="Enter your email address" id="email">
                                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="img">
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Password</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" placeholder="Enter your password" id="password">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login">
                            <div class="alreadyuser">
                                <h4><a href="{{ route('forgot.password') }}" class="hover-a">Forgot Password?</a></h4>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Sign In</button>
                        </div>
                    </form>
                    <div class="signinform text-center">
                        <h4>Don’t have an account? <a href="{{ route('register') }}" class="hover-a">Sign Up</a></h4>
                    </div>
                </div>
            </div>
            <div class="login-img">
                <img src="assets/img/login.jpg" alt="img">
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const form = document.getElementById("login_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                const email = document.getElementById("email").value;
                const password = document.getElementById("password").value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (0 == email.length) {
                    toastr.info("Email is Required", "POS Says:");
                } else if (!emailRegex.test(email)) {
                    toastr.info("Invalid email format", "POS Says:");
                } else if (0 == password.length) {
                    toastr.info("Password is Required", "POS Says:");
                } else {
                    showLoader()
                    const data = {
                        email: email,
                        password: password,
                    };
                    const URL = "{{ url('/user/login') }}";
                    const response = await axios.post(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message, "POS Says:");
                        window.location.href = "{{ route('dashboard') }}";
                    }
                }
                hideLoader()
            } catch (error) {
                if (401 == error.response.status) {
                    toastr.error(error.response.data.message, "POS Says:");
                }
                hideLoader()
            }
        });
    </script>
@endsection
