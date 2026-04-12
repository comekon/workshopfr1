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
                        {{ auth()->user()->name ?? 'User' }}
                    </span>
                    <span class="text-secondary text-small">
                        Administrator
                    </span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        {{-- DASHBOARD --}}
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- KATEGORI --}}
        <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-view-list menu-icon"></i>
            </a>
        </li>

        {{-- BUKU --}}
        <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Barang</span>
                <i class="mdi mdi-cube menu-icon"></i>
            </a>
        </li>

        {{-- SERTIFIKAT --}}
        <li class="nav-item {{ request()->routeIs('pdf.sertifikat.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.sertifikat.index') }}">
                <span class="menu-title">Sertifikat</span>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
        </li>

        {{-- Undangan --}}
        <li class="nav-item {{ request()->routeIs('pdf.pengumuman.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.pengumuman.index') }}">
                <span class="menu-title">Undangan</span>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
        </li>

        {{-- Modul 4 --}}
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-modul4" aria-expanded="false" aria-controls="ui-modul4">
                <span class="menu-title">Modul 4</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ui-modul4">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('modul4.html-table') }}">1. Tabel HTML</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('modul4.datatables') }}">2. DataTables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('modul4.select') }}">3. Select & Select2</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Modul 5 --}}
        <li class="nav-item {{ request()->routeIs('modul5.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-modul5" aria-expanded="{{ request()->routeIs('modul5.*') ? 'true' : 'false' }}" aria-controls="ui-modul5">
                <span class="menu-title">Modul 5</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-ajax menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('modul5.*') ? 'show' : '' }}" id="ui-modul5">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('modul5.wilayah.ajax') ? 'active' : '' }}"
                           href="{{ route('modul5.wilayah.ajax') }}">
                           1. Wilayah (AJAX)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('modul5.wilayah.axios') ? 'active' : '' }}"
                           href="{{ route('modul5.wilayah.axios') }}">
                           2. Wilayah (Axios)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('modul5.pos.ajax') ? 'active' : '' }}"
                           href="{{ route('modul5.pos.ajax') }}">
                           3. POS (AJAX)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('modul5.pos.axios') ? 'active' : '' }}"
                           href="{{ route('modul5.pos.axios') }}">
                           4. POS (Axios)
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Kantin Online (Customer View) --}}
        <li class="nav-item {{ request()->routeIs('kantin.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kantin.index') }}">
                <span class="menu-title">Kantin Online</span>
                <i class="mdi mdi-food-variant menu-icon"></i>
            </a>
        </li>

        {{-- Customer (Studi Kasus 3) --}}
        <li class="nav-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-customer" aria-expanded="{{ request()->routeIs('customer.*') ? 'true' : 'false' }}" aria-controls="ui-customer">
                <span class="menu-title">Customer</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('customer.*') ? 'show' : '' }}" id="ui-customer">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                            Data Customer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.create1') ? 'active' : '' }}" href="{{ route('customer.create1') }}">
                            Tambah Customer 1 (BLOB)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.create2') ? 'active' : '' }}" href="{{ route('customer.create2') }}">
                            Tambah Customer 2 (File)
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- {{-- Kantin Online (Customer View) --}}
        <li class="nav-item {{ request()->routeIs('kantin.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kantin.index') }}" target="_blank">
                <span class="menu-title">Kantin Online</span>
                <i class="mdi mdi-food-variant menu-icon"></i>
            </a>
        </li>

        {{-- Kantin Vendor --}}
        <li class="nav-item {{ request()->routeIs('vendor.*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-kantin" aria-expanded="{{ request()->routeIs('vendor.*') ? 'true' : 'false' }}" aria-controls="ui-kantin">
                <span class="menu-title">Kantin (Vendor)</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-food menu-icon"></i>
            </a>
            <div class="collapse {{ request()->routeIs('vendor.*') ? 'show' : '' }}" id="ui-kantin">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}"
                           href="{{ route('vendor.dashboard') }}">
                           Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.menu.*') ? 'active' : '' }}"
                           href="{{ route('vendor.menu.index') }}">
                           Master Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vendor.pesanan.*') ? 'active' : '' }}"
                           href="{{ route('vendor.pesanan.lunas') }}">
                           Pesanan Lunas
                        </a>
                    </li>
                </ul>
            </div>
        </li> -->
    </ul>
</nav>

