@extends('layouts.frontend')
@section('site_title', 'Verify OTP - Inventory')
@section('content')
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-0">
        <div class="container">
            <div class="header-body text-center">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <h1 class="text-white">Verify OTP</h1>
                        <p class="text-lead text-white">Please check your mail and verify OTP</p>
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
                <div class="card border-0 mb-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>Verify Otp</small>
                        </div>
                        <div style="font-weight:bold;text-align:center">
                            <p class="countdown" style="height:50px;color: #ff9f43;font-size:2rem">0:00</p>
                        </div>
                        <form id="verify_otp_form">
                            <div class="height-100 d-flex justify-content-center align-items-center">
                                <div class="position-relative">
                                    <div class="card p-2 text-center shadow-none">
                                        <h6>Please enter the one time password <br> to verify your account</h6>
                                        <div> <span>A code has been sent to</span> <small>*******9897</small> </div>
                                        <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                                            <input class="m-2 text-center form-control rounded" type="text"
                                                id="first" maxlength="1" />
                                            <input class="m-2 text-center form-control rounded" type="text"
                                                id="second" maxlength="1" />
                                            <input class="m-2 text-center form-control rounded" type="text"
                                                id="third" maxlength="1" />
                                            <input class="m-2 text-center form-control rounded" type="text"
                                                id="fourth" maxlength="1" />
                                            <input class="m-2 text-center form-control rounded" type="text"
                                                id="fifth" maxlength="1" />
                                            <input class="m-2 text-center form-control rounded" type="text"
                                                id="sixth" maxlength="1" />
                                        </div>
                                        <button class="btn btn-primary my-4 validate">Validate</button>
                                    </div>
                                </div>
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
        //otp input
        document.addEventListener("DOMContentLoaded", function(event) {
            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('keydown', function(event) {
                        if (event.key === "Backspace") {
                            inputs[i].value = '';
                            if (i !== 0) inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if (event.keyCode > 47 && event.keyCode < 58) {
                                inputs[i].value = event.key;
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            } else if (event.keyCode > 64 && event.keyCode < 91) {
                                inputs[i].value = String.fromCharCode(event.keyCode);
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            }
                        }
                    });
                }
            }
            OTPInput();
        });

        //verify otp code
        const form = document.getElementById("verify_otp_form");
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            try {
                const email = sessionStorage.getItem("email");
                const ist = document.getElementById("first").value;
                const sec = document.getElementById("second").value;
                const third = document.getElementById("third").value;
                const fourth = document.getElementById("fourth").value;
                const fifth = document.getElementById("fifth").value;
                const sixth = document.getElementById("sixth").value;
                const otp = ist + sec + third + fourth + fifth + sixth;

                if (0 == otp.length) {
                    toastr.info("OTP is Required");
                } else if (6 != otp.length) {
                    toastr.info("OTP Must be 6 characters");
                } else {
                    showLoader()
                    const data = {
                        email: email,
                        otp: otp,
                    };
                    const URL = "{{ route('verify.otp') }}";
                    const response = await axios.post(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message, "POS Says:");
                        sessionStorage.clear();
                        window.location.href = "{{ route('resetPass.page') }}";
                    }
                }
                hideLoader()
            } catch (error) {
                if (400 == error.response.status) {
                    toastr.error(error.response.data.message);
                }
                if (500 == error.response.status) {
                    toastr.error(error.response.data.message);
                }
                hideLoader()
            }
        });

        //countdown
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
@endsection
