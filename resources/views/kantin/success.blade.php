@extends('kantin.layout')

@section('title', 'Pembayaran Berhasil — Kantin Online')

@php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

function generateQrCode($text) {
    $writer = new PngWriter();
    $qrCode = new QrCode($text);
    $qrCode->setSize(200);
    $qrCode->setMargin(10);
    $result = $writer->write($qrCode);
    return $result->getDataUri();
}
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card kantin-card fade-in">
            <div class="card-body text-center py-5">
                <div style="width:90px;height:90px;border-radius:50%;margin:0 auto 20px;
                            background:linear-gradient(135deg,#43e97b,#38f9d7);
                            display:flex;align-items:center;justify-content:center;">
                    <i class="mdi mdi-check-bold text-white" style="font-size:3rem;"></i>
                </div>

                <h2 class="fw-bold mb-2">Pembayaran Berhasil!</h2>
                <p class="text-muted mb-4">Pesanan Anda telah berhasil diproses</p>

                <div class="bg-light rounded-3 p-4 mx-auto" style="max-width:420px;">
                    <table class="table table-borderless mb-0 text-start">
                        <tr>
                            <td class="text-muted">Nama Customer</td>
                            <td class="fw-bold text-end">{{ $pesanan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Order ID</td>
                            <td class="fw-bold text-end"><code>{{ $pesanan->midtrans_order_id }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal</td>
                            <td class="fw-bold text-end">{{ \Carbon\Carbon::parse($pesanan->timestamp)->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Metode Bayar</td>
                            <td class="fw-bold text-end">
                                @if($pesanan->metode_bayar == 1)
                                    <span class="badge bg-primary">Virtual Account</span>
                                @elseif($pesanan->metode_bayar == 2)
                                    <span class="badge bg-success">QRIS</span>
                                @else
                                    <span class="badge bg-secondary">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="fw-bold text-end">
                                @if($pesanan->status_bayar == 1)
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3">Detail Pesanan</h5>
                <div class="table-responsive mx-auto" style="max-width:500px;">
                    <table class="table table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start">Menu</th>
                                <th>Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->detailPesanans as $d)
                            <tr>
                                <td class="text-start">{{ $d->menu->nama_menu ?? '-' }}</td>
                                <td>{{ $d->jumlah }}</td>
                                <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="2" class="text-start">Total</td>
                                <td class="text-end" style="color:#6c63ff;">
                                    Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- QR CODE --}}
                <div class="mt-4 mb-3">
                    <h5 class="fw-bold mb-2">Scan QR Code</h5>
                    <p class="text-muted small mb-2">Scan untuk melihat detail pesanan</p>
                    <div class="bg-white rounded-3 p-3 d-inline-block shadow-sm">
                        <img src="{{ generateQrCode($pesanan->idpesanan) }}" alt="QR Code" style="width:150px;height:150px;">
                    </div>
                    <p class="text-muted small mt-2 font-monospace">ID: {{ $pesanan->idpesanan }}</p>
                </div>

                <a href="{{ route('kantin.index') }}" class="btn btn-gradient-primary btn-lg mt-4 px-5">
                    <i class="mdi mdi-cart-plus"></i> Pesan Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
