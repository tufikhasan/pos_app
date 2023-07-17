@extends('layouts.frontend')
@section('site_title', 'Verify Otp - Pos Dashboard')
@section('content')
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="img" />
                    </div>
                    <div class="login-userheading">
                        <h3>Verify OTP</h3>
                        <h4>
                            Don’t warry! it happens. Please enter the address <br />
                            associated with your account.
                        </h4>
                    </div>
                    <div class="form-login">
                        <div class="countdown" style="height: 30px"></div>
                        <div style="display:flex; gap:.5rem">
                            <input type="text" id='ist' maxlength="1" onkeyup="clickEvent(this,'sec')">
                            <input type="text" id="sec" maxlength="1" onkeyup="clickEvent(this,'third')">
                            <input type="text" id="third" maxlength="1" onkeyup="clickEvent(this,'fourth')">
                            <input type="text" id="fourth" maxlength="1" onkeyup="clickEvent(this,'fifth')">
                            <input type="text" id="fifth" maxlength="1" onkeyup="clickEvent(this,'sixth')">
                            <input type="text" id="sixth" maxlength="1">
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
<script>
    function clickEvent(first, last) {
        if (first.value.length) {
            document.getElementById(last).focus();
        }
    }

    function timer(minutes, seconds) {
        let timer = setInterval(() => {

            if (minutes < 0) {
                $('.countdown').text('');
                clearInterval(timer);
            } else {
                let tempMinutes = minutes.toString().length > 1 ? minutes : '0' + minutes;
                let tempSeconds = seconds.toString().length > 1 ? seconds : '0' + seconds;
                $('.countdown').text(tempMinutes + ':' + tempSeconds);
            }
            if (seconds <= 0) {
                minutes--;
                seconds = 59;
            }
            seconds--;
        }, 1000);
    }

    timer(2, 59);
</script>
