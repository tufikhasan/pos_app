<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="POS - Tailwind Admin Template" />
    <meta name="keywords"
        content="admin, estimates, tailwind, business, corporate, creative, management, minimal, modern,  html5, responsive" />
    <meta name="author" content="Dreamguys - Tailwind Admin Template" />
    <meta name="robots" content="noindex, nofollow" />
    <title>@yield('site_title')</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/flowbite.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('dist/tailwind-dist.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}" />
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"></div>
    </div>

    <div class="main-wrapper">
        @include('components.header')
        @include('components.sidebar')

        <div class="page-wrapper">@yield('content')</div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/flowbite.js') }}"></script>
    <script src="{{ asset('assets/js/alpine.min.js') }}" defer></script>
    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('assets/js/flowbite.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>
    <script>
        async function profileInfoShow() {
            const profile_picture = document.querySelectorAll(".profile_picture");
            const user_name = document.getElementById("user_name");
            try {
                const Profile_URL = "{{ route('profile.details') }}";
                const profileInfo = await axios.get(Profile_URL);

                user_name.innerText = profileInfo.data.first_name + ' ' + profileInfo.data.last_name

                profile_picture.forEach((item, key) => {
                    item.src = profileInfo.data.image ?
                        `{{ asset('${profileInfo.data.image}') }}` :
                        "{{ asset('assets/img/profiles/avator1.jpg') }}";;
                });
            } catch (error) {
                console.log("Something Went Wrong");
            }
        }
        profileInfoShow();
    </script>
    @yield('script')
</body>

</html>
