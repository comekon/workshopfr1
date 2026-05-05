@extends('kantin.layout')

@section('title', 'QR Code Pesanan — Kantin Online')

@php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

function generateQrCode($text) {
    $writer = new PngWriter();
    $qrCode = new QrCode($text);
    $qrCode->setSize(250);
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
                <h4 class="fw-bold mb-2">QR Code Pesanan Anda</h4>
                <p class="text-muted mb-4">Tunjukkan QR code ini ke vendor untuk pengambilan pesanan</p>

                <div class="bg-white rounded-3 p-4 d-inline-block shadow-sm mb-3">
                    <img src="{{ generateQrCode($pesanan->idpesanan) }}" alt="QR Code" style="width:200px;height:200px;">
                </div>

                <p class="font-monospace text-muted small">ID Pesanan: {{ $pesanan->idpesanan }}</p>

                <hr class="my-4">

                <div class="bg-light rounded-3 p-3 mx-auto" style="max-width:380px;">
                    <table class="table table-borderless mb-0 text-start small">
                        <tr>
                            <td class="text-muted">Customer</td>
                            <td class="fw-bold text-end">{{ $pesanan->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total</td>
                            <td class="fw-bold text-end" style="color:#6c63ff;">
                                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Bayar</td>
                            <td class="fw-bold text-end">
                                @if($pesanan->status_bayar == 1)
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($pesanan->status_bayar == 2)
                                    <span class="badge bg-danger">Gagal</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ route('kantin.index') }}" class="btn btn-gradient-primary px-4">
                        <i class="mdi mdi-cart-plus me-1"></i> Pesan Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
