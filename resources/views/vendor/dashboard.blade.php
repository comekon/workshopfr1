@extends('vendor.layouts.app')

@section('title', 'Dashboard Vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-store"></i>
                </span> Dashboard Vendor
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vendor</li>
                </ul>
            </nav>
        </div>
    </div>
</div>

{{-- Info Vendor --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="mdi mdi-store-check me-2" style="font-size:1.5rem;"></i>
            <div>
                Anda login sebagai vendor: <strong>{{ $vendor->nama_vendor }}</strong>
            </div>
        </div>
    </div>
</div>

{{-- Statistik Cards --}}
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="">
                <h4 class="font-weight-normal mb-3">
                    Total Menu
                    <i class="mdi mdi-food-variant mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-0">{{ $totalMenu }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="">
                <h4 class="font-weight-normal mb-3">
                    Pesanan Lunas
                    <i class="mdi mdi-check-decagram mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-0">{{ $pesananLunas }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="">
                <h4 class="font-weight-normal mb-3">
                    Total Pendapatan
                    <i class="mdi mdi-cash-multiple mdi-24px float-end"></i>
                </h4>
                <h2 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="mdi mdi-food-variant text-primary" style="font-size:3rem;"></i>
                <h5 class="mt-3 mb-2">Master Menu</h5>
                <p class="text-muted">Kelola menu makanan & minuman</p>
                <a href="{{ route('vendor.menu.index') }}" class="btn btn-gradient-primary">
                    <i class="mdi mdi-arrow-right"></i> Kelola Menu
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="mdi mdi-receipt text-success" style="font-size:3rem;"></i>
                <h5 class="mt-3 mb-2">Pesanan Lunas</h5>
                <p class="text-muted">Lihat pesanan yang sudah dibayar</p>
                <a href="{{ route('vendor.pesanan.lunas') }}" class="btn btn-gradient-success">
                    <i class="mdi mdi-arrow-right"></i> Lihat Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
