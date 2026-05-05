@extends('vendor.layouts.app')

@section('title', 'Scan QR Pesanan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-qrcode-scan"></i>
                </span> Scan QR Pesanan
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Vendor</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Scan QR</li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">

                {{-- Scanner --}}
                <div id="scanner-section">
                    <div id="reader" style="width: 100%;"></div>
                    <p class="text-muted text-center mt-2 small">Arahkan kamera ke QR code customer</p>
                </div>

                {{-- Hasil Scan --}}
                <div id="result-section" style="display: none;">
                    <div class="alert alert-success">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-check-circle me-2" style="font-size:1.5rem;"></i>
                            <div>
                                <h5 class="alert-heading mb-0">QR Code Terbaca!</h5>
                                <small class="mb-0">Pesanan dari <strong id="res-nama"></strong></small>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="text-muted">Order ID</span>
                                    <div class="fw-bold"><code id="res-idpesanan"></code></div>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted">Status Bayar</span>
                                    <div id="res-status-bayar"></div>
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-bold mb-2">Menu yang Dipesan</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Menu</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="res-detail"></tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td colspan="3" class="text-end">Total</td>
                                            <td class="text-end" id="res-total"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
    var scanner = new Html5Qrcode("reader");
    var processed = false;

    function playBeep() {
        var ctx = new (window.AudioContext || window.webkitAudioContext)();
        var osc = ctx.createOscillator();
        var gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = 1200;
        osc.type = 'square';
        gain.gain.value = 0.3;
        osc.start();
        osc.stop(ctx.currentTime + 0.15);
    }

    function setBadge(el, text, colorClass) {
        el.textContent = '';
        var badge = document.createElement('span');
        badge.className = 'badge ' + colorClass;
        badge.textContent = text;
        el.appendChild(badge);
    }

    function startScan() {
        processed = false;
        document.getElementById('scanner-section').style.display = 'block';
        document.getElementById('result-section').style.display = 'none';

        scanner.start(
            { facingMode: "environment" },
            { fps: 30, qrbox: function(viewfinderWidth, viewfinderHeight) {
                var minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                var qrboxSize = Math.floor(minEdge * 0.75);
                return { width: Math.max(qrboxSize, 150), height: Math.max(qrboxSize, 150) };
            }},
            onScanSuccess,
            function() {}
        ).then(function() {
            // scanner started
        }).catch(function(err) {
            console.error('Scanner start error:', err);
            Swal.fire('Error', 'Tidak dapat mengakses kamera: ' + err, 'error');
        });
    }

    function onScanSuccess(decodedText) {
        if (processed) return;
        processed = true;

        playBeep();
        scanner.stop().catch(function() {});

        document.getElementById('scanner-section').style.display = 'none';

        var idpesanan = decodedText.trim();

        axios.get('/api/kantin/pesanan/' + encodeURIComponent(idpesanan))
            .then(function(res) {
                if (res.data.status === 'success') {
                    var p = res.data;

                    document.getElementById('res-nama').textContent = p.nama;
                    document.getElementById('res-idpesanan').textContent = p.idpesanan;

                    var statusEl = document.getElementById('res-status-bayar');
                    if (p.status_bayar == 1) {
                        setBadge(statusEl, 'Lunas', 'bg-success');
                    } else if (p.status_bayar == 2) {
                        setBadge(statusEl, 'Gagal', 'bg-danger');
                    } else {
                        setBadge(statusEl, 'Pending', 'bg-warning');
                    }

                    var tbody = document.getElementById('res-detail');
                    while (tbody.firstChild) tbody.removeChild(tbody.firstChild);

                    p.detail.forEach(function(d) {
                        var tr = document.createElement('tr');

                        var tdMenu = document.createElement('td');
                        tdMenu.textContent = d.nama_menu;
                        if (d.catatan) {
                            var br = document.createElement('br');
                            var small = document.createElement('small');
                            small.className = 'text-muted fst-italic';
                            small.textContent = d.catatan;
                            tdMenu.appendChild(br);
                            tdMenu.appendChild(small);
                        }
                        tr.appendChild(tdMenu);

                        var tdQty = document.createElement('td');
                        tdQty.className = 'text-center';
                        tdQty.textContent = d.jumlah;
                        tr.appendChild(tdQty);

                        var tdHarga = document.createElement('td');
                        tdHarga.className = 'text-end';
                        tdHarga.textContent = 'Rp ' + Number(d.harga).toLocaleString('id-ID');
                        tr.appendChild(tdHarga);

                        var tdSub = document.createElement('td');
                        tdSub.className = 'text-end';
                        tdSub.textContent = 'Rp ' + Number(d.subtotal).toLocaleString('id-ID');
                        tr.appendChild(tdSub);

                        tbody.appendChild(tr);
                    });

                    document.getElementById('res-total').textContent = 'Rp ' + Number(p.total).toLocaleString('id-ID');
                    document.getElementById('result-section').style.display = 'block';
                } else {
                    Swal.fire('Tidak Ditemukan', 'Pesanan tidak ditemukan.', 'warning');
                    startScan();
                }
            })
            .catch(function(err) {
                console.error('API Error:', err);
                Swal.fire('Error', 'Gagal mengambil data pesanan.', 'error');
                startScan();
            });
    }

    document.getElementById('btn-scan-again').addEventListener('click', startScan);

    startScan();
</script>
@endpush
