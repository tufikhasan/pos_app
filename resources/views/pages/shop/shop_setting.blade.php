@php
    $user_role = request()->header('role');
@endphp
@extends('layouts.backend')
@section('site_title', 'Shop Settings')
@section('content')
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0">Shop Settings</h3>
                    <p class="text-sm mb-0">
                        Update Your Shop Information
                    </p>
                </div>
                <div class="card-body">
                    <form id="shop_update_form">
                        <div class="form-group">
                            <label for="shop_name" class="form-control-label">Shop Name</label>
                            <input class="form-control" type="text" id="shop_name" value="{{ $shop->shop_name }}"
                                name="shop_name">
                        </div>
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <div class="custom-file">
                                <input type="file" oninput="up_shop_logo.src=window.URL.createObjectURL(this.files[0])"
                                    class="custom-file-input" id="logo" name="logo">
                            </div>
                        </div>
                        <img width="70"
                            src="{{ file_exists(public_path('upload/shop/' . $shop->logo)) ? asset('upload/shop/' . $shop->logo) : asset('assets/img/no_image.jpg') }}"
                            id="up_shop_logo">
                        @if (in_array($user_role, ['admin', 'manager']))
                            <div class="text-right">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        //update brand listener
        const shop_update_form = document.getElementById("shop_update_form");
        shop_update_form.addEventListener("submit", async (e) => {
            e.preventDefault();
            try {
                const data = new FormData(shop_update_form);
                const shop_name = data.get("shop_name");
                const logo = data.get("logo");

                if (shop_name.length == 0) {
                    toastr.info("Shop Name is required");
                } else if (logo.size > 0.5 * 1024 * 1024) {
                    toastr.info("You can upload a maximum of 512 KB.");
                } else {
                    showLoader();
                    const updateURL = "{{ route('shop.update') }}";
                    const response = await axios.post(updateURL, data, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    });
                    hideLoader();
                    if (response.status == 200 && response.data.status == 'success') {
                        if (logo.size) {
                            document.getElementById('shop_logo').src = URL.createObjectURL(logo);
                        }
                        toastr.success(response.data.message);
                    }
                    if (response.status == 200 && response.data.status == 'failed') {
                        toastr.error(response.data.message);
                    }
                }
            } catch (error) {
                hideLoader();
                if (error.response.status == 400) {
                    toastr.error(error.response.data.message);
                }
                if (error.response.status == 403) {
                    if (error.response.data.message.name) {
                        toastr.error(error.response.data.message.name);
                    }
                    if (error.response.data.message.image) {
                        toastr.error(error.response.data.message.image);
                    }
                }
            }
        });
    </script>
@endsection
