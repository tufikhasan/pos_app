@extends('layouts.frontend')
@section('site_title', 'Shop - Management')
@section('content')
    <!-- Header -->
    <div class="header bg-primary pt-5 pb-7">
        <div class="container">
            <div class="header-body">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="pr-5">
                            <h1 class="display-2 text-white font-weight-bold mb-0">Shop Management</h1>
                            <p class="text-white mt-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eum est sed
                                optio tempora voluptatibus earum cum distinctio similique. Beatae hic praesentium blanditiis
                                earum fugit molestias!</p>
                            <div class="mt-5">
                                <a href="{{ route('signup.page') }}" class="btn btn-neutral my-2">Start</a>
                                <a href="{{ route('signin.page') }}" class="btn btn-default my-2">Login</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row pt-5">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow mb-4">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <h5 class="h3">Staff Section</h5>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, in!</p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow mb-4">
                                            <i class="fas fa-sleigh"></i>
                                        </div>
                                        <h5 class="h3">Sales Section</h5>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, in!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pt-lg-5 pt-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div
                                            class="icon icon-shape bg-gradient-success text-white rounded-circle shadow mb-4">
                                            <i class="fas fa-seedling"></i>
                                        </div>
                                        <h5 class="h3">Product Section</h5>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, in!</p>
                                    </div>
                                </div>
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div
                                            class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow mb-4">
                                            <i class="far fa-file-alt"></i>
                                        </div>
                                        <h5 class="h3">Reports Section</h5>
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit, in!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>
    <section class="py-6">
        <div class="container">
            <div class="row row-grid align-items-center text-light">
                <div class="col-md-6 order-md-2">
                    <img src="{{ asset('assets/img/landing-1.png') }}" class="img-fluid">
                </div>
                <div class="col-md-6 order-md-1">
                    <div class="pr-md-5">
                        <h1 class="text-light">Awesome features</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur alias dolorum porro. Ut
                            voluptatum illo tempora nostrum, aliquid laudantium quam, natus exercitationem magnam enim
                            adipisci!</p>
                        <ul class="list-unstyled mt-5">
                            <li class="py-2">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="badge badge-circle badge-success mr-3">
                                            <i class="ni ni-settings-gear-65"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-light">Lorem ipsum dolor sit amet consectetur</h4>
                                    </div>
                                </div>
                            </li>
                            <li class="py-2">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="badge badge-circle badge-success mr-3">
                                            <i class="ni ni-html5"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-light">Lorem ipsum dolor sit amet consectetur</h4>
                                    </div>
                                </div>
                            </li>
                            <li class="py-2">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="badge badge-circle badge-success mr-3">
                                            <i class="ni ni-satisfied"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-light">Lorem ipsum dolor sit amet consectetur</h4>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-6">
        <div class="container">
            <div class="row row-grid align-items-center text-light">
                <div class="col-md-6">
                    <img src="{{ asset('assets/img/landing-2.png') }}" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <div class="pr-md-5">
                        <h1 class="text-light">Sales Feature</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur alias dolorum porro. Ut
                            voluptatum illo tempora nostrum, aliquid laudantium quam, natus exercitationem magnam enim
                            adipisci!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-6">
        <div class="container">
            <div class="row row-grid align-items-center text-light">
                <div class="col-md-6 order-md-2">
                    <img src="{{ asset('assets/img/landing-3.png') }}" class="img-fluid">
                </div>
                <div class="col-md-6 order-md-1">
                    <div class="pr-md-5">
                        <h1 class="text-light">Reports Generate</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur alias dolorum porro. Ut
                            voluptatum illo tempora nostrum, aliquid laudantium quam, natus exercitationem magnam enim
                            adipisci!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')

@endsection
