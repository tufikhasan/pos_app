@extends('layouts.frontend')
@section('site_title', 'Verify Otp - Pos Dashboard')
@section('content')
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <div class="login-userset">
                    <div class="login-logo">
                        <a href="{{ route('login') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="img" /></a>
                    </div>
                    <div class="login-userheading">
                        <h3>Verify OTP</h3>
                        <h4>Don’t Share your OTP anyone</h4>
                    </div>
                    <div class="form-login">
                        <div style="font-weight:bold;text-align:center">
                            <p class="countdown" style="height:50px;color: #ff9f43;font-size:2rem"></p>
                        </div>
                        <form id="verify_otp_form">
                            <div style="display: flex; gap: 0.5rem">
                                <input type="text" id="ist" maxlength="1" onkeyup="clickEvent(this,'sec')" />
                                <input type="text" id="sec" maxlength="1" onkeyup="clickEvent(this,'third')" />
                                <input type="text" id="third" maxlength="1" onkeyup="clickEvent(this,'fourth')" />
                                <input type="text" id="fourth" maxlength="1" onkeyup="clickEvent(this,'fifth')" />
                                <input type="text" id="fifth" maxlength="1" onkeyup="clickEvent(this,'sixth')" />
                                <input type="text" id="sixth" maxlength="1" />
                            </div>
                    </div>
                    <div class="form-login">
                        <button type="submit" class="btn btn-login">Verify OTP</button>
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
        function clickEvent(first, last) {
            if (first.value.length) {
                document.getElementById(last).focus();
            }
        }

        function timer(minutes, seconds) {
            let timer = setInterval(() => {
                if (minutes < 0) {
                    $(".countdown").text("");
                    clearInterval(timer);
                } else {
                    let tempMinutes =
                        minutes.toString().length > 1 ? minutes : "0" + minutes;
                    let tempSeconds =
                        seconds.toString().length > 1 ? seconds : "0" + seconds;
                    $(".countdown").text(tempMinutes + ":" + tempSeconds);
                }
                if (seconds <= 0) {
                    minutes--;
                    seconds = 59;
                }
                seconds--;
            }, 1000);
        }
        //count down timer show
        (async () => {
            try {
                showLoader()
                const em = sessionStorage.getItem('email');
                const timeUrl = "{{ route('countdown', ':email') }}".replace(':email', encodeURIComponent(em));
                // const timeUrl = `/countdown/${em}`;
                const resTime = await axios.get(timeUrl);
                hideLoader()
                if (resTime.data.current_time <= resTime.data.update_time) {
                    timer(resTime.data.minutes, resTime.data.seconds);
                }
            } catch (error) {
                console.log('something went wrong');
                hideLoader()
            }
        })()
    </script>
    <script>
        const form = document.getElementById("verify_otp_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                const email = sessionStorage.getItem("email");
                const ist = document.getElementById("ist").value;
                const sec = document.getElementById("sec").value;
                const third = document.getElementById("third").value;
                const fourth = document.getElementById("fourth").value;
                const fifth = document.getElementById("fifth").value;
                const sixth = document.getElementById("sixth").value;
                const otp = ist + sec + third + fourth + fifth + sixth;

                if (0 == otp.length) {
                    toastr.info("OTP is Required", "POS Says:");
                } else if (6 != otp.length) {
                    toastr.info("OTP Must be 6 characters", "POS Says:");
                } else {
                    showLoader()
                    const data = {
                        email: email,
                        otp: otp,
                    };
                    const URL = "{{ url('/verify/otp') }}";
                    const response = await axios.post(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message, "POS Says:");
                        sessionStorage.clear();
                        window.location.href = "{{ route('reset.password') }}";
                    }
                }
                hideLoader()
            } catch (error) {
                if (400 == error.response.status) {
                    toastr.error(error.response.data.message, "POS Says:");
                }
                if (500 == error.response.status) {
                    toastr.error(error.response.data.message, "POS Says:");
                }
                hideLoader()
            }
        });
    </script>
@endsection
