@extends('layouts.app')

@section('title', 'Scan Barcode — Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="mdi mdi-barcode-scan me-2"></i> Scan Barcode Barang
                </h4>

                {{-- Scanner Area --}}
                <div id="scanner-section">
                    <div id="reader" style="width: 100%;"></div>
                    <p class="text-muted text-center mt-2 small">Arahkan kamera ke barcode pada label kertas</p>
                </div>

                {{-- Hasil Scan --}}
                <div id="result-section" style="display: none;">
                    <div class="alert alert-success" role="alert">
                        <div class="d-flex align-items-center mb-2">
                            <i class="mdi mdi-check-circle me-2" style="font-size:1.5rem;"></i>
                            <h5 class="alert-heading mb-0">Barcode Terbaca!</h5>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="text-muted" width="35%">ID Barang</td>
                                    <td class="fw-bold" id="res-id"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Barang</td>
                                    <td class="fw-bold" id="res-nama"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Harga</td>
                                    <td class="fw-bold text-primary" id="res-harga"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <button id="btn-scan-again" class="btn btn-primary mt-3">
                        <i class="mdi mdi-refresh me-1"></i> Scan Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    const scanner = new Html5Qrcode("reader");
    let scanning = false;
    let processed = false;

    function playBeep() {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = 1200;
        osc.type = 'square';
        gain.gain.value = 0.3;
        osc.start();
        osc.stop(ctx.currentTime + 0.15);
    }

    function startScan() {
        processed = false;
        document.getElementById('scanner-section').style.display = 'block';
        document.getElementById('result-section').style.display = 'none';

        scanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 150 } },
            onScanSuccess,
            () => {}
        ).then(() => {
            scanning = true;
        }).catch(err => {
            console.error(err);
            Swal.fire('Error', 'Tidak dapat mengakses kamera: ' + err, 'error');
        });
    }

    function onScanSuccess(decodedText) {
        if (processed) return;
        processed = true;

        playBeep();

        // Stop scanner di background, jangan tunggu resolve-nya
        scanner.stop().catch(function() {});
        scanning = false;

        document.getElementById('scanner-section').style.display = 'none';

        // Fetch data barang langsung, tanpa menunggu scanner.stop()
        axios.get('/api/barang/' + encodeURIComponent(decodedText.trim()))
            .then(function(res) {
                if (res.data.status === 'success') {
                    document.getElementById('res-id').textContent = res.data.id_barang;
                    document.getElementById('res-nama').textContent = res.data.nama_barang;
                    document.getElementById('res-harga').textContent = 'Rp ' + Number(res.data.harga).toLocaleString('id-ID');
                    document.getElementById('result-section').style.display = 'block';
                } else {
                    Swal.fire('Tidak Ditemukan', 'Barang dengan ID "' + decodedText + '" tidak ditemukan.', 'warning');
                    startScan();
                }
            })
            .catch(function(err) {
                console.error('API Error:', err);
                Swal.fire('Error', 'Gagal mengambil data barang.', 'error');
                startScan();
            });
    }

    document.getElementById('btn-scan-again').addEventListener('click', startScan);

    // Auto start
    startScan();
</script>
@endpush
