<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo" href="{{ route('vendor.dashboard') }}">
            <span class="text-primary font-weight-bold">
                <i class="mdi mdi-store me-1"></i>VendorPanel
            </span>
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('vendor.dashboard') }}">
            <i class="mdi mdi-store text-primary" style="font-size:1.5rem;"></i>
        </a>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-stretch">

        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        {{-- Badge Vendor --}}
        <div class="d-flex align-items-center ms-3">
            <span class="badge badge-gradient-primary">
                <i class="mdi mdi-food-variant me-1"></i> Vendor Panel
            </span>
        </div>

        <ul class="navbar-nav navbar-nav-right ms-auto">

            {{-- Link ke Kantin Online --}}
            <li class="nav-item d-none d-lg-block">
                <a class="nav-link" href="{{ route('kantin.index') }}" target="_blank">
                    <i class="mdi mdi-open-in-new me-1"></i> Lihat Kantin
                </a>
            </li>

            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                    <div class="nav-profile-img">
                        <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="image">
                        <span class="availability-status online"></span>
                    </div>
                    <div class="nav-profile-text">
                        <p class="mb-1 text-black">
                            {{ auth()->user()->nama ?? 'Vendor' }}
                        </p>
                        <small class="text-secondary text-small">Vendor</small>
                    </div>
                </a>

                <div class="dropdown-menu navbar-dropdown dropdown-menu-end">

                    <a class="dropdown-item" href="{{ route('vendor.dashboard') }}">
                        <i class="mdi mdi-store me-2 text-primary"></i>
                        Dashboard Vendor
                    </a>

                    <div class="dropdown-divider"></div>

                    {{-- LOGOUT --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="mdi mdi-logout me-2 text-danger"></i>
                            Logout
                        </button>
                    </form>

                </div>
            </li>

            <li class="nav-item d-none d-lg-block full-screen-link">
                <a class="nav-link">
                    <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </a>
            </li>

            <li class="nav-item nav-logout d-none d-lg-block">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent">
                        <i class="mdi mdi-power"></i>
                    </button>
                </form>
            </li>

        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
            type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>

    </div>
</nav>
