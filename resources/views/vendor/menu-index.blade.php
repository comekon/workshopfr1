@extends('vendor.layouts.app')

@section('title', 'Master Menu')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-food-variant"></i>
                </span> Master Menu
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Menu</li>
                </ul>
            </nav>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        Daftar Menu — {{ $vendor->nama_vendor }}
                    </h4>
                    <a href="{{ route('vendor.menu.create') }}" class="btn btn-gradient-primary btn-sm">
                        <i class="mdi mdi-plus"></i> Tambah Menu
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Gambar</th>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $idx => $menu)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="text-center">
                                    @if($menu->path_gambar)
                                        <img src="{{ asset('storage/menu-images/' . $menu->path_gambar) }}"
                                             alt="{{ $menu->nama_menu }}"
                                             style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                                    @else
                                        <div style="width:60px;height:60px;border-radius:8px;margin:0 auto;
                                                    background:linear-gradient(135deg,#e0c3fc,#8ec5fc);
                                                    display:flex;align-items:center;justify-content:center;">
                                            <i class="mdi mdi-food text-white" style="font-size:1.5rem;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $menu->nama_menu }}</td>
                                <td class="text-center">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('vendor.menu.edit', $menu->idmenu) }}"
                                       class="btn btn-warning btn-sm me-1">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('vendor.menu.destroy', $menu->idmenu) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('Yakin hapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-delete"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="mdi mdi-food-off" style="font-size:2rem;"></i><br>
                                    Belum ada menu. <a href="{{ route('vendor.menu.create') }}">Tambahkan menu pertama</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
