@extends('layouts.backend')
@section('site_title', 'Change Password')
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Change Password</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="xl:grid xl:grid-cols-2 gap-x-6">
            <div class="flex">
                <div class="card flex-auto">
                    <div class="card-header">
                        <h5 class="card-title">Update Your password</h5>
                    </div>
                    <div class="card-body">
                        <form id="password_update_form">
                            <div class="form-group lg:grid lg:grid-cols-12 gap-x-6">
                                <label class="col-span-3 col-form-label" for="old_password">Old Password</label>
                                <div class="col-span-9">
                                    <input id="old_password" type="password" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group lg:grid lg:grid-cols-12 gap-x-6">
                                <label class="col-span-3 col-form-label" for="new_password">New Password</label>
                                <div class="col-span-9">
                                    <input id="new_password" type="password" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group lg:grid lg:grid-cols-12 gap-x-6">
                                <label class="col-span-3 col-form-label" for="confirm_password">Confirm Password</label>
                                <div class="col-span-9">
                                    <input id="confirm_password" type="password" class="form-control" />
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
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
        const password_update_form = document.getElementById(
            "password_update_form"
        );
        password_update_form.addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const old_password = document.getElementById("old_password").value;
                const new_password = document.getElementById("new_password").value;
                const confirm_password =
                    document.getElementById("confirm_password").value;

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
                    const URL = "{{ route('password.update') }}";
                    const data = {
                        old_password: old_password,
                        new_password: new_password,
                        confirm_password: confirm_password,
                    };
                    const res = await axios.patch(URL, data);
                    if (res.status == 200 && res.data.status == "success") {
                        toastr.success(res.data.message, "POS Says:");
                        password_update_form.reset();
                    } else if (res.status == 200 && res.data.status == "failed") {
                        toastr.info(res.data.message, "POS Says:");
                    }
                }
            } catch (error) {
                console.log("Something Went Wrong");
            }
        });
    </script>
@endsection
