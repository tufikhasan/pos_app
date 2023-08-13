@php
    $route = Route::current()->getName();
@endphp
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scroll-wrapper scrollbar-inner" style="position: relative;">
        <div class="scrollbar-inner scroll-content scroll-scrolly_visible"
            style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 354px;">
            <!-- Brand -->
            <div class="sidenav-header  d-flex  align-items-center">
                <a class="navbar-brand" href="javascript:void(0)">
                    <img src="{{ asset('assets/img/brand/blue.png') }}" class="navbar-brand-img" alt="...">
                </a>
                <div class=" ml-auto ">
                    <!-- Sidenav toggler -->
                    <div class="sidenav-toggler d-none d-xl-block active" data-action="sidenav-unpin"
                        data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-inner">
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <!-- Nav items -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ in_array($route, ['dashboard', 'profile.page', 'change.password']) ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="ni ni-shop text-primary"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ 'customer.page' == $route ? 'active' : '' }}"
                                href="{{ route('customer.page') }}">
                                <i class="ni ni-circle-08 text-red"></i>
                                <span class="nav-link-text">Customers</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ in_array($route, ['brand.page', 'category.page', 'product.page']) ? 'active' : '' }}"
                                href="#navbar-products" data-toggle="collapse" role="button"
                                aria-expanded="{{ in_array($route, ['brand.page', 'category.page', 'product.page']) ? 'true' : 'false' }}"
                                aria-controls="navbar-products">
                                <i class="ni ni-circle-08 text-primary"></i>
                                <span class="nav-link-text">Products</span>
                            </a>
                            <div class="collapse {{ in_array($route, ['brand.page', 'category.page', 'product.page']) ? 'show' : '' }}"
                                id="navbar-products">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('brand.page') }}"
                                            class="nav-link {{ 'brand.page' == $route ? 'active' : '' }}">
                                            <span class="sidenav-mini-icon"> D </span>
                                            <span class="sidenav-normal"> Brands </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('category.page') }}"
                                            class="nav-link {{ 'category.page' == $route ? 'active' : '' }}">
                                            <span class="sidenav-mini-icon"> D </span>
                                            <span class="sidenav-normal"> Categories </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('product.page') }}"
                                            class="nav-link {{ 'product.page' == $route ? 'active' : '' }}">
                                            <span class="sidenav-mini-icon"> D </span>
                                            <span class="sidenav-normal"> Products </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ 'staffs.page' == $route ? 'active' : '' }}"
                                href="{{ route('staffs.page') }}">
                                <i class="ni ni-circle-08 text-red"></i>
                                <span class="nav-link-text">Staffs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ in_array($route, ['expense_category.page', 'expense.page']) ? 'active' : '' }}"
                                href="#navbar-expenses" data-toggle="collapse" role="button"
                                aria-expanded="{{ in_array($route, ['expense_category.page', 'expense.page']) ? 'true' : 'false' }}"
                                aria-controls="navbar-expenses">
                                <i class="ni ni-circle-08 text-primary"></i>
                                <span class="nav-link-text">Expense</span>
                            </a>
                            <div class="collapse {{ in_array($route, ['expense_category.page', 'expense.page']) ? 'show' : '' }}"
                                id="navbar-expenses">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('expense_category.page') }}"
                                            class="nav-link {{ 'expense_category.page' == $route ? 'active' : '' }}">
                                            <span class="sidenav-mini-icon"> D </span>
                                            <span class="sidenav-normal"> Expense Categories </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('expense.page') }}"
                                            class="nav-link {{ 'expense.page' == $route ? 'active' : '' }}">
                                            <span class="sidenav-mini-icon"> D </span>
                                            <span class="sidenav-normal"> Expenses </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @if (in_array(request()->header('role'), ['admin', 'manager']))
                            <li class="nav-item">
                                <a class="nav-link {{ 'promotion.page' == $route ? 'active' : '' }}"
                                    href="{{ route('promotion.page') }}">
                                    <i class="ni ni-circle-08 text-red"></i>
                                    <span class="nav-link-text">Promotion Mail</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="scroll-element scroll-x scroll-scrolly_visible">
            <div class="scroll-element_outer">
                <div class="scroll-element_size"></div>
                <div class="scroll-element_track"></div>
                <div class="scroll-bar" style="width: 0px; left: 0px;"></div>
            </div>
        </div>
        <div class="scroll-element scroll-y scroll-scrolly_visible">
            <div class="scroll-element_outer">
                <div class="scroll-element_size"></div>
                <div class="scroll-element_track"></div>
                <div class="scroll-bar" style="height: 111px; top: 0px;"></div>
            </div>
        </div>
    </div>
</nav>
