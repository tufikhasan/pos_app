@extends('layouts.backend')
@section('site_title', 'Profile')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Profile</h4>
                <h6>User Profile</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="profile-set">
                    <div class="profile-head"></div>
                    <form class="profile-top" id="profile_image_update" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="profile-content">
                            <div class="profile-contentimg">
                                <img src="" alt="img" id="blah" />
                                <div class="profileupload">
                                    <input type="file" name="image" id="imgInp" />

                                    <a href="javascript:void(0);"><img src="assets/img/icons/edit-set.svg"
                                            alt="img" /></a>
                                </div>
                            </div>
                            <div class="profile-contentname">
                                <h2 id="users_name"></h2>
                                <h4>Updates Your Photo and Personal Details.</h4>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <button type="button" onclick="uploadImage()" class="btn btn-submit mr-2">
                                Save
                            </button>
                            <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                        </div>
                    </form>
                </div>
                <form class="row" id="profile_update">
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" id="first_name" />
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" id="last_name" />
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input style="background-color: darkgray" type="text" id="email" disabled />
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" id="mobile" />
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-submit mr-2">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const users_name = document.getElementById('users_name');
        const first_name = document.getElementById("first_name");
        const last_name = document.getElementById("last_name");
        const email = document.getElementById("email");
        const mobile = document.getElementById("mobile");
        const image = document.getElementById("blah");

        //Profile details get
        async function profileDetails() {
            try {
                const profileUrl = "{{ route('profile.details') }}";
                const profileData = await axios.get(profileUrl);

                image.src = profileData.data.image ?
                    `{{ asset('${profileData.data.image}') }}` :
                    "{{ asset('assets/img/customer/customer5.jpg') }}";

                first_name.value = profileData.data.first_name;
                last_name.value = profileData.data.last_name;
                users_name.innerText = profileData.data.first_name + ' ' + profileData.data.last_name;
                email.value = profileData.data.email;
                mobile.value = profileData.data.mobile;
            } catch (error) {
                console.log("Something Went Wrong");
            }
        }
        profileDetails();

        //profile image update
        async function uploadImage() {
            try {
                const form = document.getElementById("profile_image_update");
                const formData = new FormData(form);

                const response = await axios.post(
                    "{{ route('profile.image') }}",
                    formData, {
                        headers: {
                            "Content-Type": "multipart/form-data"
                        }
                    }
                );
                console.log(response.data); // Print the response data
                if (200 == response.status && "success" == response.data.status) {
                    toastr.success(response.data.message, "POS Says:");
                    profileDetails();
                    profileInfoShow();
                }
                if (
                    200 == response.status &&
                    "not-change" == response.data.status
                ) {
                    toastr.info(response.data.message, "POS Says:");
                }
            } catch (error) {
                console.log(error);
            }
        }

        //update profile details
        const profile_form = document.getElementById("profile_update");
        profile_form.addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                if (0 == first_name.value.length) {
                    toastr.info("First Name is Required", "POS Says:");
                } else if (0 == last_name.value.length) {
                    toastr.info("Last Name is Required", "POS Says:");
                } else if (0 == mobile.value.length) {
                    toastr.info("Mobile Number is Required", "POS Says:");
                } else {
                    const data = {
                        first_name: first_name.value,
                        last_name: last_name.value,
                        mobile: mobile.value,
                    };
                    const URL = "{{ route('profile.update') }}";
                    const response = await axios.patch(URL, data);
                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message, "POS Says:");
                        profileDetails();
                        profileInfoShow();
                    }
                }
            } catch (error) {
                console.log("Something Went Wrong");
            }
        });
    </script>
@endsection
