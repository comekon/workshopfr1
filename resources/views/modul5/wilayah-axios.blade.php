@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">

        {{-- Page Header --}}
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-success text-white me-2">
                    <i class="mdi mdi-map-marker-multiple"></i>
                </span> Wilayah Administrasi (Axios)
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Modul 5</li>
                    <li class="breadcrumb-item active" aria-current="page">Wilayah (Axios)</li>
                </ul>
            </nav>
        </div>

        {{-- Card Utama --}}
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="card-title text-success"><i class="mdi mdi-map-marker-multiple me-2"></i> Pilih Wilayah</h4>

                {{-- Info Badge --}}
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="mdi mdi-information-outline me-2 fs-5"></i>
                    <div>
                        Pilihan <strong>Kota/Kabupaten</strong> bergantung pada <strong>Provinsi</strong> yang dipilih.
                        Pilihan <strong>Kecamatan</strong> bergantung pada <strong>Kota</strong>, dan seterusnya.
                        Menggunakan <strong>Axios</strong> berbasis <em>Promise</em>.
                    </div>
                </div>

                <div class="row g-4">

                    {{-- Level 1: Provinsi --}}
                    <div class="col-md-6">
                        <label for="prov_select" class="form-label fw-semibold">
                            <span class="badge bg-success rounded-pill me-1">1</span>
                            Provinsi
                        </label>
                        <select id="prov_select" class="form-select form-select-lg wilayah-select"
                                onchange="loadKota(this.value)">
                            <option value="">— Pilih Provinsi —</option>
                            @foreach ($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Level 2: Kota --}}
                    <div class="col-md-6">
                        <label for="kota_select" class="form-label fw-semibold">
                            <span class="badge bg-secondary rounded-pill me-1">2</span>
                            Kota / Kabupaten
                        </label>
                        <div class="position-relative">
                            <select id="kota_select" class="form-select form-select-lg wilayah-select"
                                    disabled onchange="loadKecamatan(this.value)">
                                <option value="">— Pilih Kota/Kabupaten —</option>
                            </select>
                            <div id="spin_kota" class="select-spinner d-none">
                                <span class="spinner-border spinner-border-sm text-success"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Level 3: Kecamatan --}}
                    <div class="col-md-6">
                        <label for="kec_select" class="form-label fw-semibold">
                            <span class="badge bg-secondary rounded-pill me-1">3</span>
                            Kecamatan
                        </label>
                        <div class="position-relative">
                            <select id="kec_select" class="form-select form-select-lg wilayah-select"
                                    disabled onchange="loadKelurahan(this.value)">
                                <option value="">— Pilih Kecamatan —</option>
                            </select>
                            <div id="spin_kecamatan" class="select-spinner d-none">
                                <span class="spinner-border spinner-border-sm text-success"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Level 4: Kelurahan --}}
                    <div class="col-md-6">
                        <label for="kel_select" class="form-label fw-semibold">
                            <span class="badge bg-secondary rounded-pill me-1">4</span>
                            Kelurahan / Desa
                        </label>
                        <div class="position-relative">
                            <select id="kel_select" class="form-select form-select-lg wilayah-select"
                                    disabled onchange="updateHasil()">
                                <option value="">— Pilih Kelurahan/Desa —</option>
                            </select>
                            <div id="spin_kelurahan" class="select-spinner d-none">
                                <span class="spinner-border spinner-border-sm text-success"></span>
                            </div>
                        </div>
                    </div>

                </div>{{-- end row --}}

                {{-- Hasil --}}
                <div class="mt-4" id="hasil_section" style="display:none;">
                    <hr>
                    <h6 class="fw-bold text-success mb-3">
                        <i class="mdi mdi-check-circle me-1"></i> Hasil Pilihan
                    </h6>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-success-subtle bg-success-subtle">
                                <small class="text-muted d-block">Provinsi</small>
                                <strong id="r_provinsi" class="text-dark">-</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-success-subtle bg-success-subtle">
                                <small class="text-muted d-block">Kota/Kabupaten</small>
                                <strong id="r_kota" class="text-dark">-</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-success-subtle bg-success-subtle">
                                <small class="text-muted d-block">Kecamatan</small>
                                <strong id="r_kecamatan" class="text-dark">-</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded border border-success-subtle bg-success-subtle">
                                <small class="text-muted d-block">Kelurahan/Desa</small>
                                <strong id="r_kelurahan" class="text-dark">-</strong>
                            </div>
                        </div>
                    </div>
                </div>

                    </div>
                </div>{{-- end card --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js-page')
{{-- Axios dari CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// ──────────────────────────────────────────────────────
// Helper: reset select
// ──────────────────────────────────────────────────────
function resetSelect(id, placeholder) {
    var el = document.getElementById(id);
    el.innerHTML = '<option value="">' + placeholder + '</option>';
    el.disabled = true;
}

function showSpinner(id) {
    document.getElementById('spin_' + id).classList.remove('d-none');
}

function hideSpinner(id) {
    document.getElementById('spin_' + id).classList.add('d-none');
}

function buildOptions(data, placeholder) {
    var html = '<option value="">' + placeholder + '</option>';
    data.forEach(function (item) {
        html += '<option value="' + item.id + '">' + item.name + '</option>';
    });
    return html;
}

function updateHasil() {
    var prov = document.getElementById('prov_select');
    var kota = document.getElementById('kota_select');
    var kec  = document.getElementById('kec_select');
    var kel  = document.getElementById('kel_select');

    var anySelected = prov.value || kota.value || kec.value || kel.value;

    if (anySelected) {
        document.getElementById('hasil_section').style.display = '';
        document.getElementById('r_provinsi').textContent  = prov.value ? prov.options[prov.selectedIndex].text : '-';
        document.getElementById('r_kota').textContent      = kota.value ? kota.options[kota.selectedIndex].text : '-';
        document.getElementById('r_kecamatan').textContent = kec.value  ? kec.options[kec.selectedIndex].text  : '-';
        document.getElementById('r_kelurahan').textContent = kel.value  ? kel.options[kel.selectedIndex].text  : '-';
    } else {
        document.getElementById('hasil_section').style.display = 'none';
    }
}

// ──────────────────────────────────────────────────────
// Load Kota berdasarkan Provinsi — menggunakan Axios
// ──────────────────────────────────────────────────────
function loadKota(province_id) {
    // Reset semua level bawah
    resetSelect('kota_select', '— Pilih Kota/Kabupaten —');
    resetSelect('kec_select', '— Pilih Kecamatan —');
    resetSelect('kel_select', '— Pilih Kelurahan/Desa —');
    updateHasil();

    if (!province_id) return;

    showSpinner('kota');

    axios.get('/api/wilayah/regencies/' + province_id)
        .then(function (response) {
            // response.data → objek JSON dari Laravel
            // response.data.data → array kota
            hideSpinner('kota');

            var kotaEl = document.getElementById('kota_select');
            kotaEl.innerHTML = buildOptions(response.data.data, '— Pilih Kota/Kabupaten —');
            kotaEl.disabled = false;
            updateHasil();
        })
        .catch(function (error) {
            hideSpinner('kota');
            console.error('Axios error - kota:', error);
            alert('Gagal memuat data kota. Silakan coba lagi.');
        });
}

// ──────────────────────────────────────────────────────
// Load Kecamatan berdasarkan Kota — menggunakan Axios
// ──────────────────────────────────────────────────────
function loadKecamatan(regency_id) {
    resetSelect('kec_select', '— Pilih Kecamatan —');
    resetSelect('kel_select', '— Pilih Kelurahan/Desa —');
    updateHasil();

    if (!regency_id) return;

    showSpinner('kecamatan');

    axios.get('/api/wilayah/districts/' + regency_id)
        .then(function (response) {
            hideSpinner('kecamatan');

            var kecEl = document.getElementById('kec_select');
            kecEl.innerHTML = buildOptions(response.data.data, '— Pilih Kecamatan —');
            kecEl.disabled = false;
            updateHasil();
        })
        .catch(function (error) {
            hideSpinner('kecamatan');
            console.error('Axios error - kecamatan:', error);
            alert('Gagal memuat data kecamatan. Silakan coba lagi.');
        });
}

// ──────────────────────────────────────────────────────
// Load Kelurahan berdasarkan Kecamatan — menggunakan Axios
// ──────────────────────────────────────────────────────
function loadKelurahan(district_id) {
    resetSelect('kel_select', '— Pilih Kelurahan/Desa —');
    updateHasil();

    if (!district_id) return;

    showSpinner('kelurahan');

    axios.get('/api/wilayah/villages/' + district_id)
        .then(function (response) {
            hideSpinner('kelurahan');

            var kelEl = document.getElementById('kel_select');
            kelEl.innerHTML = buildOptions(response.data.data, '— Pilih Kelurahan/Desa —');
            kelEl.disabled = false;
            updateHasil();
        })
        .catch(function (error) {
            hideSpinner('kelurahan');
            console.error('Axios error - kelurahan:', error);
            alert('Gagal memuat data kelurahan. Silakan coba lagi.');
        });
}
</script>

<style>
.wilayah-select {
    border-radius: 8px;
    transition: all 0.2s ease;
}
.wilayah-select:focus {
    border-color: #05a34a;
    box-shadow: 0 0 0 0.2rem rgba(5, 163, 74, 0.2);
}
.wilayah-select:disabled {
    background-color: #f8f9fa;
    cursor: not-allowed;
    opacity: 0.7;
}
.select-spinner {
    position: absolute;
    right: 40px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}
.bg-success-subtle {
    background-color: #d1e7dd !important;
}
.border-success-subtle {
    border-color: #a3cfbb !important;
}
</style>
@endpush
