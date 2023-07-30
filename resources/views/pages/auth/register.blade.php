@extends('layouts.frontend')
@section('site_title', 'Register - Pos Dashboard')
@section('content')
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <a href="{{ route('login') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="img" /></a>
                    </div>
                    <div class="login-userheading">
                        <h3>Create an Account</h3>
                        <h4>Continue where you left off</h4>
                    </div>
                    <form id="register_form">
                        <div class="form-login">
                            <label>First Name</label>
                            <div class="form-addons">
                                <input id="first_name" type="text" placeholder="Enter First Name" />
                                <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img" />
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Last Name</label>
                            <div class="form-addons">
                                <input id="last_name" type="text" placeholder="Enter Last Name" />
                                <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img" />
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Email</label>
                            <div class="form-addons">
                                <input id="email" type="text" placeholder="Enter your email address" />
                                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="img" />
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Mobile</label>
                            <div class="form-addons">
                                <input id="mobile" type="tel" pattern="((\+8801)|(01))\d{9}"
                                    placeholder="+8801xxxxxxxxx / 01xxxxxxxxx" />
                                <img src="{{ asset('assets/img/icons/mobile.svg') }}" alt="img" />
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Password</label>
                            <div class="pass-group">
                                <input id="password" type="password" class="pass-input"
                                    placeholder="Enter your password" />
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">
                                Sign Up
                            </button>
                        </div>
                    </form>
                    <div class="signinform text-center">
                        <h4>
                            Already a user?
                            <a href="{{ route('login') }}" class="hover-a">Sign In</a>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="login-img">
                <img src="assets/img/login.jpg" alt="img" />
            </div>
        </div>
    </div>
    @endsection @section('script')
    <script>
        const form = document.getElementById("register_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                const first_name = document.getElementById("first_name").value;
                const last_name = document.getElementById("last_name").value;
                const email = document.getElementById("email").value;
                const mobile = document.getElementById("mobile").value;
                const password = document.getElementById("password").value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (0 == first_name.length) {
                    toastr.info("First Name is Required", "POS Says:");
                } else if (0 == last_name.length) {
                    toastr.info("Last Name is Required", "POS Says:");
                } else if (0 == email.length) {
                    toastr.info("Email is Required", "POS Says:");
                } else if (!emailRegex.test(email)) {
                    toastr.info("Invalid email format", "POS Says:");
                } else if (0 == mobile.length) {
                    toastr.info("Mobile Number is Required", "POS Says:");
                } else if (0 == password.length) {
                    toastr.info("Password is Required", "POS Says:");
                } else {
                    showLoader()
                    const data = {
                        first_name: first_name,
                        last_name: last_name,
                        email: email,
                        mobile: mobile,
                        password: password,
                    };
                    const URL = "{{ url('/register') }}";
                    const response = await axios.post(URL, data);
                    if (
                        201 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message, "POS Says:");
                        window.location.href = "{{ route('login') }}";
                    } else if (
                        200 == response.status &&
                        "failed" == response.data.status
                    ) {
                        toastr.error(response.data.message, "POS Says:");
                    }
                }
                hideLoader()
            } catch (error) {
                if (403 == error.response.status) {
                    toastr.error(error.response.data.message.email, "POS Says:");
                }
                hideLoader()
            }
        });
    </script>
@endsection
