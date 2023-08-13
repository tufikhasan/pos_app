@extends('layouts.backend')
@section('site_title', 'Profile - Page')
@section('content')
    <div class="row mt-4">
        <div class="col-lg-4 order-xl-2">
            <div class="card-wrapper">
                <div class="card card-profile pt-4">
                    <div class="text-center">
                        <img src="{{ asset('assets/img/no_image.jpg') }}" class="rounded-circle" alt="..." width="140px"
                            id="profileImage">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center display-4 user_name"></h5>
                        <div class="card-text">
                            <p class="small"><b>Shop Name: </b><span id="user_shop_name"></span></p>
                            <p class="small"><b>Role: </b><span id="user_role"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-xl-1">
            <div class="card-wrapper">
                <!-- HTML5 inputs -->
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">Edit profile</h3>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                        <form id="profile_update">
                            <div class="form-group row">
                                <label for="name" class="col-md-2 col-form-label form-control-label">Name</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" id="name" name="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-2 col-form-label form-control-label">Email</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="email" id="email" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mobile" class="col-md-2 col-form-label form-control-label">Mobile</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="tel" id="mobile" name="mobile">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="image" class="col-md-2 col-form-label form-control-label">Profile
                                    Picture</label>
                                <div class="col-md-10">
                                    <input oninput="profileImage.src=window.URL.createObjectURL(this.files[0])"
                                        class="form-control" type="file" name="image" id="image">
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary my-4">Update Profile</button>
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
        //Profile details get
        async function profileDetails() {
            try {
                showLoader();
                const profileUrl = "{{ route('profile.details') }}";
                const profileData = await axios.get(profileUrl);
                document.getElementById("name").value = profileData.data.name;
                document.getElementById("email").value = profileData.data.email;
                document.getElementById("mobile").value = profileData.data.mobile;
                document.getElementById("profileImage").src = profileData.data.image ? profileData.data.image :
                    "{{ asset('assets/img/no_image.jpg') }}";
                document.getElementById("user_shop_name").innerText = profileData.data.shop.shop_name;
                document.getElementById("user_role").innerText = profileData.data.role;
                hideLoader();
            } catch (error) {
                hideLoader();
                console.log("Something Went Wrong");
                console.log(error)
            }
        }
        profileDetails();


        //update profile details
        const profile_form = document.getElementById("profile_update");
        profile_form.addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const data = new FormData(profile_form);

                const name = data.get("name");
                const mobile = data.get("mobile");
                const image = data.get("image");
                const mobileNumberRegex = /^(\+8801|01)[1-9][0-9]{8}$/;

                if (name.length == 0) {
                    toastr.info("Name is required");
                } else if (!mobileNumberRegex.test(mobile) && mobile) {
                    toastr.info("Invalid mobile format");
                } else if (image.size > 0.5 * 1024 * 1024) {
                    toastr.info(
                        "You can upload a maximum of 512 KB image.");
                } else {
                    showLoader();
                    const URL = "{{ route('profile.update') }}";
                    const response = await axios.post(URL, data);

                    if (
                        200 == response.status &&
                        "success" == response.data.status
                    ) {
                        toastr.success(response.data.message);
                        profileDetails();
                        document.getElementById('tobBarName').innerText = name;
                    }
                    hideLoader();
                }
            } catch (error) {
                hideLoader();
                if (error.response.status == 400 && error.response.data.message.email) {
                    toastr.error(error.response.data.message.email);
                } else {
                    console.log("Something Went Wrong");
                }
            }
        });
        $(document).ready(function() {
            $("#image").change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#tobBarImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>
@endsection
