@extends('layouts.frontend')
@section('site_title', 'Forgot Password - Pos Dashboard')
@section('content')
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="img" />
                    </div>
                    <div class="login-userheading">
                        <h3>Forgot password?</h3>
                        <h4>
                            Don’t warry! it happens. Please enter the address <br />
                            associated with your account.
                        </h4>
                    </div>
                    <div class="form-login">
                        <label>Email</label>
                        <div class="form-addons">
                            <input type="text" placeholder="Enter your email address" />
                            <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="img" />
                        </div>
                    </div>
                    <div class="form-login">
                        <a class="btn btn-login" href="signin.html">Submit</a>
                    </div>
                </div>
            </div>
            <div class="login-img">
                <img src="{{ asset('assets/img/login.jpg') }}" alt="img" />
            </div>
        </div>
    </div>
@endsection
