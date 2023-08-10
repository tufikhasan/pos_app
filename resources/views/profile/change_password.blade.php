@extends('layouts.backend')
@section('site_title', 'Profile - Page')
@section('content')
    <div class="row mt-4">
        <div class="col-lg-6">
            <!-- HTML5 inputs -->
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Password Change</h3>
                </div>
                <!-- Card body -->
                <div class="card-body">
                    <form id="password_update_form">
                        <div class="form-group row">
                            <label for="old_password" class="col-md-2 col-form-label form-control-label">Old Password</label>
                            <div class="col-md-10">
                                <input class="form-control" type="password" id="old_password" name="old_password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password" class="col-md-2 col-form-label form-control-label">New
                                Password</label>
                            <div class="col-md-10">
                                <input class="form-control" type="password" id="new_password" name="new_password">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirm_password" class="col-md-2 col-form-label form-control-label">Confirm
                                Password</label>
                            <div class="col-md-10">
                                <input class="form-control" type="password" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary my-4">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const password_update_form = document.getElementById(
            "password_update_form"
        );
        password_update_form.addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const old_password = document.getElementById("old_password").value;
                const new_password = document.getElementById("new_password").value;
                const confirm_password = document.getElementById("confirm_password").value;

                if (old_password.length == 0) {
                    toastr.info("Old Password Is Required", "POS Says:");
                } else if (new_password.length == 0) {
                    toastr.info("New Password Is Required", "POS Says:");
                } else if (confirm_password.length == 0) {
                    toastr.info("Confirm Password Is Required", "POS Says:");
                } else if (new_password != confirm_password) {
                    toastr.info(
                        "New Password & Confirm Password must be same",
                        "POS Says:"
                    );
                } else {
                    showLoader();
                    const URL = "{{ route('password.update') }}";
                    const data = {
                        old_password: old_password,
                        new_password: new_password,
                        confirm_password: confirm_password,
                    };
                    const res = await axios.patch(URL, data);
                    hideLoader();
                    if (res.status == 200 && res.data.status == "success") {
                        toastr.success(res.data.message);
                        password_update_form.reset();
                    } else if (res.status == 200 && res.data.status == "failed") {
                        toastr.info(res.data.message);
                    }
                }
            } catch (error) {
                hideLoader();
                console.log("Something Went Wrong");
            }
        });
    </script>
@endsection
