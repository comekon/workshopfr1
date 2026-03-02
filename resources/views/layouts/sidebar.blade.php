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
    </ul>
</nav>
