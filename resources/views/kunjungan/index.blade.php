@extends('layouts.app')

@section('title', 'Kunjungan Toko')

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            <i class="mdi mdi-map-marker-radius me-2"></i> Kunjungan Toko
        </h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- TAB NAVIGATION --}}
        <ul class="nav nav-tabs" id="kunjunganTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-list" data-bs-toggle="tab" href="#panel-list" role="tab">
                    <i class="mdi mdi-format-list-bulleted me-1"></i> List Toko
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-input" data-bs-toggle="tab" href="#panel-input" role="tab">
                    <i class="mdi mdi-map-marker-plus me-1"></i> Input Titik Awal
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-kunjungan" data-bs-toggle="tab" href="#panel-kunjungan" role="tab">
                    <i class="mdi mdi-walk me-1"></i> Titik Kunjungan
                </a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="kunjunganTabContent">

            {{-- ============================================ --}}
            {{-- TAB 1: LIST TOKO --}}
            {{-- ============================================ --}}
            <div class="tab-pane fade show active" id="panel-list" role="tabpanel">
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Nama Toko</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Accuracy (m)</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($toko as $item)
                        <tr>
                            <td><code>{{ $item->barcode }}</code></td>
                            <td>{{ $item->nama_toko }}</td>
                            <td>{{ $item->latitude }}</td>
                            <td>{{ $item->longitude }}</td>
                            <td>{{ $item->accuracy }}</td>
                            <td>
                                <form action="{{ route('kunjungan.cetak') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="barcodes[]" value="{{ $item->barcode }}">
                                    <input type="hidden" name="start_x" value="1">
                                    <input type="hidden" name="start_y" value="1">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-barcode me-1"></i> Cetak
                                    </button>
                                </form>
                                <button type="button" class="btn btn-warning btn-sm btn-edit-toko"
                                    data-barcode="{{ $item->barcode }}"
                                    data-nama="{{ $item->nama_toko }}"
                                    data-lat="{{ $item->latitude }}"
                                    data-lng="{{ $item->longitude }}"
                                    data-acc="{{ $item->accuracy }}">
                                    Edit
                                </button>
                                <form action="{{ route('kunjungan.destroy', $item->barcode) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus toko ini?')" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    @if($toko->isEmpty())
                        <tr><td colspan="6" class="text-center text-muted">Belum ada data toko</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>

            {{-- ============================================ --}}
            {{-- TAB 2: INPUT TITIK AWAL --}}
            {{-- ============================================ --}}
            <div class="tab-pane fade" id="panel-input" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Input Titik Awal Toko</h5>

                                <form id="formInputTitik" action="{{ route('kunjungan.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Barcode Toko</label>
                                        <input type="text" name="barcode" class="form-control" maxlength="8" required placeholder="Contoh: TOKO001">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Toko</label>
                                        <input type="text" name="nama_toko" class="form-control" maxlength="50" required placeholder="Contoh: Toko Maju Jaya">
                                    </div>

                                    <hr>

                                    {{-- Pilihan metode input --}}
                                    <div class="btn-group w-100 mb-3" role="group">
                                        <button type="button" class="btn btn-primary active" id="btnMetodeGps">
                                            <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi GPS
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" id="btnMetodeManual">
                                            <i class="mdi mdi-pencil me-1"></i> Input Manual
                                        </button>
                                    </div>

                                    {{-- Metode GPS --}}
                                    <div id="metodeGps">
                                        <button type="button" id="btnAmbilLokasiInput" class="btn btn-primary mb-3 w-100">
                                            <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi
                                        </button>
                                        <div id="lokasiInputStatus" class="alert alert-info d-none"></div>
                                    </div>

                                    {{-- Metode Manual --}}
                                    <div id="metodeManual" class="d-none">
                                        <div class="mb-3">
                                            <label class="form-label">Latitude</label>
                                            <input type="number" step="any" name="manual_latitude" class="form-control" placeholder="-6.200000">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Longitude</label>
                                            <input type="number" step="any" name="manual_longitude" class="form-control" placeholder="106.816000">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Accuracy (meter)</label>
                                            <input type="number" step="any" name="manual_accuracy" class="form-control" value="200" min="0">
                                        </div>
                                    </div>

                                    <div class="mb-3 d-none" id="groupLatInput">
                                        <label class="form-label">Latitude</label>
                                        <input type="text" name="latitude" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3 d-none" id="groupLngInput">
                                        <label class="form-label">Longitude</label>
                                        <input type="text" name="longitude" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3 d-none" id="groupAccInput">
                                        <label class="form-label">Accuracy (meter)</label>
                                        <input type="text" name="accuracy" class="form-control" readonly>
                                    </div>

                                    <button type="submit" id="btnSubmitInput" class="btn btn-success w-100">
                                        <i class="mdi mdi-content-save me-1"></i> Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- TAB 3: TITIK KUNJUNGAN --}}
            {{-- ============================================ --}}
            <div class="tab-pane fade" id="panel-kunjungan" role="tabpanel">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        {{-- Scanner --}}
                        <div id="scanner-section">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-3">Scan Barcode Toko</h5>
                                    <div id="reader" style="width: 100%;"></div>
                                    <p class="text-muted small mt-2">Arahkan kamera ke barcode toko</p>
                                </div>
                            </div>
                        </div>

                        {{-- Hasil Scan + Kunjungan --}}
                        <div id="kunjungan-section" style="display: none;">

                            {{-- Data Toko --}}
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="mdi mdi-store me-1"></i> Data Toko</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted" width="35%">Barcode</td>
                                            <td class="fw-bold" id="k-barcode"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Nama Toko</td>
                                            <td class="fw-bold" id="k-nama"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Latitude</td>
                                            <td id="k-toko-lat"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Longitude</td>
                                            <td id="k-toko-lng"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Accuracy Toko</td>
                                            <td id="k-toko-acc"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- Ambil Lokasi Sales --}}
                            <button type="button" id="btnAmbilLokasiKunjungan" class="btn btn-primary w-100 mb-3">
                                <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi Kunjungan
                            </button>

                            <div id="lokasiKunjunganStatus" class="alert alert-info d-none"></div>

                            {{-- Hasil Kunjungan --}}
                            <div id="hasil-kunjungan" class="card d-none">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="mdi mdi-map-marker me-1"></i> Hasil Kunjungan</h5>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="text-muted" width="35%">Latitude Sales</td>
                                            <td id="k-sales-lat"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Longitude Sales</td>
                                            <td id="k-sales-lng"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Accuracy Sales</td>
                                            <td id="k-sales-acc"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Jarak Aktual</td>
                                            <td class="fw-bold" id="k-jarak"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Threshold Efektif</td>
                                            <td id="k-threshold-efektif"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-bold">Status</td>
                                            <td id="k-status"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <button type="button" id="btnScanLagi" class="btn btn-outline-primary mt-3">
                                <i class="mdi mdi-refresh me-1"></i> Scan Lagi
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL EDIT TOKO --}}
<div class="modal fade" id="modalEditToko" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditToko" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Toko</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-barcode" name="barcode">
                    <div class="mb-3">
                        <label class="form-label">Nama Toko</label>
                        <input type="text" id="edit-nama" name="nama_toko" class="form-control" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="any" id="edit-lat" name="latitude" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="any" id="edit-lng" name="longitude" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Accuracy (meter)</label>
                        <input type="number" step="any" id="edit-acc" name="accuracy" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js-page')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// ==========================================
// GEOLOCATION HELPER
// ==========================================
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;
                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }
                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }
                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) resolve(bestResult);
                    else reject(new Error("Timeout, tidak dapat posisi"));
                }
            },
            (error) => reject(error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

// ==========================================
// HAVERSINE FORMULA (meter)
// ==========================================
function haversine(lat1, lng1, lat2, lng2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) ** 2
            + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
            * Math.sin(dLng / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// ==========================================
// TAB 2: INPUT TITIK AWAL
// ==========================================
const btnAmbilLokasiInput = document.getElementById('btnAmbilLokasiInput');
const lokasiInputStatus = document.getElementById('lokasiInputStatus');
const formInputTitik = document.getElementById('formInputTitik');
let inputMetode = 'gps';

// Toggle metode GPS / Manual
document.getElementById('btnMetodeGps').addEventListener('click', () => {
    inputMetode = 'gps';
    document.getElementById('metodeGps').classList.remove('d-none');
    document.getElementById('metodeManual').classList.add('d-none');
    document.getElementById('groupLatInput').classList.add('d-none');
    document.getElementById('groupLngInput').classList.add('d-none');
    document.getElementById('groupAccInput').classList.add('d-none');
    document.getElementById('btnMetodeGps').classList.add('active', 'btn-primary');
    document.getElementById('btnMetodeGps').classList.remove('btn-outline-primary');
    document.getElementById('btnMetodeManual').classList.remove('active', 'btn-primary');
    document.getElementById('btnMetodeManual').classList.add('btn-outline-primary');
    formInputTitik.manual_latitude.removeAttribute('required');
    formInputTitik.manual_longitude.removeAttribute('required');
    formInputTitik.manual_accuracy.removeAttribute('required');
});

document.getElementById('btnMetodeManual').addEventListener('click', () => {
    inputMetode = 'manual';
    document.getElementById('metodeGps').classList.add('d-none');
    document.getElementById('metodeManual').classList.remove('d-none');
    document.getElementById('groupLatInput').classList.add('d-none');
    document.getElementById('groupLngInput').classList.add('d-none');
    document.getElementById('groupAccInput').classList.add('d-none');
    document.getElementById('btnMetodeManual').classList.add('active', 'btn-primary');
    document.getElementById('btnMetodeManual').classList.remove('btn-outline-primary');
    document.getElementById('btnMetodeGps').classList.remove('active', 'btn-primary');
    document.getElementById('btnMetodeGps').classList.add('btn-outline-primary');
    formInputTitik.manual_latitude.setAttribute('required', '');
    formInputTitik.manual_longitude.setAttribute('required', '');
    formInputTitik.manual_accuracy.setAttribute('required', '');
});

// Submit form - handle GPS vs Manual
formInputTitik.addEventListener('submit', (e) => {
    if (inputMetode === 'manual') {
        formInputTitik.latitude.value = formInputTitik.manual_latitude.value;
        formInputTitik.longitude.value = formInputTitik.manual_longitude.value;
        formInputTitik.accuracy.value = formInputTitik.manual_accuracy.value;
    }
});

btnAmbilLokasiInput.addEventListener('click', async () => {
    btnAmbilLokasiInput.disabled = true;
    btnAmbilLokasiInput.textContent = 'Mengambil lokasi...';
    lokasiInputStatus.classList.remove('d-none');
    lokasiInputStatus.textContent = 'Mengambil lokasi GPS...';

    try {
        const pos = await getAccuratePosition(50);
        formInputTitik.latitude.value = pos.coords.latitude.toFixed(8);
        formInputTitik.longitude.value = pos.coords.longitude.toFixed(8);
        formInputTitik.accuracy.value = pos.coords.accuracy.toFixed(2);

        document.getElementById('groupLatInput').classList.remove('d-none');
        document.getElementById('groupLngInput').classList.remove('d-none');
        document.getElementById('groupAccInput').classList.remove('d-none');
        lokasiInputStatus.classList.add('d-none');
    } catch (err) {
        lokasiInputStatus.classList.remove('d-none', 'alert-info');
        lokasiInputStatus.classList.add('alert-danger');
        lokasiInputStatus.textContent = 'Gagal mengambil lokasi: ' + err.message;
    } finally {
        btnAmbilLokasiInput.disabled = false;
        btnAmbilLokasiInput.textContent = 'Ambil Lokasi';
    }
});

// ==========================================
// TAB 3: TITIK KUNJUNGAN - BARCODE SCANNER
// ==========================================
const scanner = new Html5Qrcode("reader");
let scanning = false;
let processed = false;
let currentToko = null;

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
    document.getElementById('kunjungan-section').style.display = 'none';
    document.getElementById('hasil-kunjungan').classList.add('d-none');

    scanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 150 } },
        onScanSuccess,
        () => {}
    ).then(() => {
        scanning = true;
    }).catch(err => {
        Swal.fire('Error', 'Tidak dapat mengakses kamera: ' + err, 'error');
    });
}

function onScanSuccess(decodedText) {
    if (processed) return;
    processed = true;
    playBeep();

    scanner.stop().catch(() => {});
    scanning = false;

    document.getElementById('scanner-section').style.display = 'none';

    axios.get('/api/kunjungan/toko/' + encodeURIComponent(decodedText.trim()))
        .then(res => {
            if (res.data.status === 'success') {
                currentToko = res.data;
                document.getElementById('k-barcode').textContent = res.data.barcode;
                document.getElementById('k-nama').textContent = res.data.nama_toko;
                document.getElementById('k-toko-lat').textContent = res.data.latitude;
                document.getElementById('k-toko-lng').textContent = res.data.longitude;
                document.getElementById('k-toko-acc').textContent = res.data.accuracy + ' m';
                document.getElementById('kunjungan-section').style.display = 'block';
            } else {
                Swal.fire('Tidak Ditemukan', 'Toko tidak ditemukan.', 'warning');
                startScan();
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Gagal mengambil data toko.', 'error');
            startScan();
        });
}

// Ambil lokasi kunjungan
document.getElementById('btnAmbilLokasiKunjungan').addEventListener('click', async () => {
    if (!currentToko) return;

    const btn = document.getElementById('btnAmbilLokasiKunjungan');
    const statusEl = document.getElementById('lokasiKunjunganStatus');
    btn.disabled = true;
    btn.textContent = 'Mengambil lokasi...';
    statusEl.classList.remove('d-none');
    statusEl.textContent = 'Mengambil lokasi GPS...';

    try {
        const pos = await getAccuratePosition(50);
        const salesLat = pos.coords.latitude;
        const salesLng = pos.coords.longitude;
        const salesAcc = pos.coords.accuracy;
        const tokoLat = parseFloat(currentToko.latitude);
        const tokoLng = parseFloat(currentToko.longitude);
        const tokoAcc = parseFloat(currentToko.accuracy);
        const threshold = 200;

        const jarak = haversine(tokoLat, tokoLng, salesLat, salesLng);
        const thresholdEfektif = threshold + tokoAcc + salesAcc;
        const diterima = jarak <= thresholdEfektif;

        document.getElementById('k-sales-lat').textContent = salesLat.toFixed(8);
        document.getElementById('k-sales-lng').textContent = salesLng.toFixed(8);
        document.getElementById('k-sales-acc').textContent = salesAcc.toFixed(2) + ' m';
        document.getElementById('k-jarak').textContent = jarak.toFixed(2) + ' m';
        document.getElementById('k-threshold-efektif').textContent = thresholdEfektif.toFixed(2) + ' m';

        const statusResult = document.getElementById('k-status');
        statusResult.textContent = '';
        const badge = document.createElement('span');
        badge.classList.add('badge', 'fs-6');
        if (diterima) {
            badge.classList.add('bg-success');
            badge.textContent = 'DITERIMA';
        } else {
            badge.classList.add('bg-danger');
            badge.textContent = 'DITOLAK';
        }
        statusResult.appendChild(badge);

        document.getElementById('hasil-kunjungan').classList.remove('d-none');
        statusEl.classList.add('d-none');
    } catch (err) {
        statusEl.classList.remove('d-none', 'alert-info');
        statusEl.classList.add('alert-danger');
        statusEl.textContent = 'Gagal: ' + err.message;
    } finally {
        btn.disabled = false;
        btn.textContent = 'Ambil Lokasi Kunjungan';
    }
});

// Scan lagi
document.getElementById('btnScanLagi').addEventListener('click', startScan);

// ==========================================
// EDIT TOKO MODAL
// ==========================================
const modalEditToko = new bootstrap.Modal(document.getElementById('modalEditToko'));
const formEditToko = document.getElementById('formEditToko');

document.querySelectorAll('.btn-edit-toko').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit-barcode').value = btn.dataset.barcode;
        document.getElementById('edit-nama').value = btn.dataset.nama;
        document.getElementById('edit-lat').value = btn.dataset.lat;
        document.getElementById('edit-lng').value = btn.dataset.lng;
        document.getElementById('edit-acc').value = btn.dataset.acc;
        formEditToko.action = '{{ url("kunjungan") }}/' + btn.dataset.barcode;
        modalEditToko.show();
    });
});

// Auto-start scanner when tab 3 is shown
document.getElementById('tab-kunjungan').addEventListener('shown.bs.tab', () => {
    if (!scanning && !processed) {
        startScan();
    }
});

// Stop scanner when leaving tab 3
document.getElementById('tab-kunjungan').addEventListener('hidden.bs.tab', () => {
    if (scanning) {
        scanner.stop().catch(() => {});
        scanning = false;
    }
});
</script>
@endpush
