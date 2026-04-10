<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">
                        {{ auth()->user()->nama ?? 'Vendor' }}
                    </span>
                    <span class="text-secondary text-small">
                        Vendor Panel
                    </span>
                </div>
                <i class="mdi mdi-store text-success nav-profile-badge"></i>
            </a>
        </li>

        {{-- Dashboard Vendor --}}
        <li class="nav-item {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendor.dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- Kantin Vendor Menu --}}
        <li class="nav-item {{ request()->routeIs('vendor.menu.*') || request()->routeIs('vendor.pesanan.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-kantin" aria-expanded="{{ request()->routeIs('vendor.menu.*') || request()->routeIs('vendor.pesanan.*') ? 'true' : 'false' }}" aria-controls="ui-kantin">
                <span class="menu-title">Manajemen Kantin</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-food menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('vendor.menu.*') || request()->routeIs('vendor.pesanan.*') ? 'show' : '' }}" id="ui-kantin">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.menu.*') ? 'active' : '' }}"
                           href="{{ route('vendor.menu.index') }}">
                           <i class="mdi mdi-food-variant me-1"></i> Master Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.pesanan.lunas') ? 'active' : '' }}"
                           href="{{ route('vendor.pesanan.lunas') }}">
                           <i class="mdi mdi-receipt me-1"></i> Pesanan Lunas
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item nav-category">Links</li>

        {{-- Lihat Kantin (Customer View) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('kantin.index') }}" target="_blank">
                <span class="menu-title">Lihat Kantin Online</span>
                <i class="mdi mdi-open-in-new menu-icon"></i>
            </a>
        </li>

        {{-- Kembali ke Admin --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Kembali ke Admin</span>
                <i class="mdi mdi-arrow-left menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
