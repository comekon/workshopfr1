@extends('vendor.layouts.app')

@section('title', 'Pesanan Lunas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-success text-white me-2">
                    <i class="mdi mdi-receipt"></i>
                </span> Pesanan Lunas
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesanan Lunas</li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        Pesanan Lunas — {{ $vendor->nama_vendor }}
                    </h4>
                    <span class="badge badge-success badge-pill" style="font-size:0.9rem;">
                        {{ $pesanans->total() }} pesanan
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="5%">#</th>
                                <th>Customer</th>
                                <th>Order ID</th>
                                <th>Waktu</th>
                                <th>Item Dipesan</th>
                                <th>Subtotal</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanans as $idx => $pesanan)
                            <tr>
                                <td class="text-center">{{ $pesanans->firstItem() + $idx }}</td>
                                <td class="fw-semibold">{{ $pesanan->nama }}</td>
                                <td><code>{{ $pesanan->midtrans_order_id }}</code></td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($pesanan->timestamp)->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($pesanan->detailPesanans as $detail)
                                        <li class="d-flex justify-content-between">
                                            <span>
                                                {{ $detail->menu->nama_menu ?? '-' }}
                                                <small class="text-muted">&times;{{ $detail->jumlah }}</small>
                                            </span>
                                            <span class="text-muted small">
                                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                            </span>
                                        </li>
                                        @if($detail->catatan)
                                        <li class="text-muted small fst-italic ms-2">↳ {{ $detail->catatan }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center fw-bold" style="color:#6c63ff;">
                                    @php
                                        $subtotal = $pesanan->detailPesanans->sum('subtotal');
                                    @endphp
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($pesanan->metode_bayar == 1)
                                        <span class="badge bg-primary">VA</span>
                                    @elseif($pesanan->metode_bayar == 2)
                                        <span class="badge bg-success">QRIS</span>
                                    @else
                                        <span class="badge bg-secondary">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="mdi mdi-receipt" style="font-size:2rem;"></i><br>
                                    Belum ada pesanan lunas
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $pesanans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
