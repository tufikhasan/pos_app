@extends('layouts.frontend')
@section('site_title', 'Forgot Password - Pos Dashboard')
@section('content')
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <a href="{{ route('login') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="img" /></a>
                    </div>
                    <div class="login-userheading">
                        <h3>Forgot password?</h3>
                        <h4>Please enter your account email address to receive an OTP (One-Time Password) in your email for
                            changing your password.</h4>
                    </div>
                    <form id="forget_form">
                        <div class="form-login">
                            <label>Email</label>
                            <div class="form-addons">
                                <input type="text" placeholder="Enter your email address" id="email" />
                                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="img" />
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Send OTP</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="login-img">
                <img src="{{ asset('assets/img/login.jpg') }}" alt="img" />
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const form = document.getElementById("forget_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                showLoader()
                const email = document.getElementById("email").value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (0 == email.length) {
                    toastr.info("Email is Required", "POS Says:");
                } else if (!emailRegex.test(email)) {
                    toastr.info("Invalid email format", "POS Says:");
                } else {
                    const data = {
                        email: email,
                    };
                    const URL = "{{ url('/send/otp') }}";
                    const response = await axios.post(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message, "POS Says:");
                        sessionStorage.setItem('email', email);
                        window.location.href = "{{ route('verify.otp') }}";
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
