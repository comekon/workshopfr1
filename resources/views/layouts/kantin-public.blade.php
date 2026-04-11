<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
</head>
<body>

<div class="container-scroller">

    {{-- NAVBAR SIMPLIFIED (PUBLIC) --}}
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo" href="{{ route('kantin.index') }}">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('kantin.index') }}">
                <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
            </a>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-end">
            <ul class="navbar-nav navbar-nav-right">
                {{-- TOMBOL LOGIN --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="mdi mdi-login me-1"></i>
                        <span class="font-weight-medium">Login</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- MAIN CONTENT (NO SIDEBAR, FULL WIDTH) --}}
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel w-100">
            <div class="content-wrapper">
                @yield('content')
            </div>

            {{-- FOOTER --}}
            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">
                        Kantin Online — Workshop Framework
                    </span>
                </div>
            </footer>
        </div>
    </div>

</div>

{{-- JS GLOBAL --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>

@stack('js-page')

</body>
</html>
