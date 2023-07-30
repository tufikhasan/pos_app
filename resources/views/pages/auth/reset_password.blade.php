@extends('layouts.frontend')
@section('site_title', 'Reset Password - Pos Dashboard')
@section('content')
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <a href="{{ route('login') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="img" /></a>
                    </div>
                    <div class="login-userheading">
                        <h3>Reset password?</h3>
                        <h4>Create a new password</h4>
                    </div>
                    <form id="reset_password_form">
                        <div class="form-login">
                            <label for="password">New Password</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" placeholder="Enter your password" id="password">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" placeholder="Enter your password"
                                    id="confirm_password">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login">
                            <button class="btn btn-login" href="signin.html">Reset Password</button>
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
        const form = document.getElementById("reset_password_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                const password = document.getElementById("password").value;
                const confirm_password = document.getElementById("confirm_password").value;

                if (0 == password.length) {
                    toastr.info("Password is Required", "POS Says:");
                } else if (0 == confirm_password.length) {
                    toastr.info("Confirm password is Required", "POS Says:");
                } else if (password != confirm_password) {
                    toastr.info("Password and Confirm must be same", "POS Says:");
                } else {
                    showLoader()
                    const data = {
                        password: password,
                        confirm_password: confirm_password,
                    };
                    const URL = "{{ url('/reset/password') }}";
                    const response = await axios.post(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message, "POS Says:");
                        window.location.href = "{{ route('login') }}";
                    }
                    console.log(response)
                }
                hideLoader()
            } catch (error) {
                if (500 == error.response.status) {
                    toastr.error(error.response.data.message, "POS Says:");
                }
                if (401 == error.response.status) {
                    toastr.error(error.response.data.message, "POS Says:");
                }
                hideLoader()
            }
        });
    </script>
@endsection
