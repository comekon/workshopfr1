@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">

        {{-- Page Header --}}
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-map-marker-multiple"></i>
                </span> Wilayah Administrasi (AJAX)
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Modul 5</li>
                    <li class="breadcrumb-item active" aria-current="page">Wilayah (AJAX)</li>
                </ul>
            </nav>
        </div>

        {{-- Card Utama --}}
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="card-title text-primary"><i class="mdi mdi-map-marker-multiple me-2"></i> Pilih Wilayah</h4>

                {{-- Info Badge --}}
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="mdi mdi-information-outline me-2 fs-5"></i>
                    <div>
                        Pilihan <strong>Kota/Kabupaten</strong> bergantung pada <strong>Provinsi</strong> yang dipilih.
                        Pilihan <strong>Kecamatan</strong> bergantung pada <strong>Kota</strong>, dan seterusnya.
                        Menggunakan <strong>AJAX jQuery</strong>.
                    </div>
                </div>

                <div class="row g-4">

                    {{-- Level 1: Provinsi --}}
                    <div class="col-md-6">
                        <label for="select_provinsi" class="form-label fw-semibold">
                            <span class="badge bg-primary rounded-pill me-1">1</span>
                            Provinsi
                        </label>
                        <select id="select_provinsi" class="form-select form-select-lg wilayah-select">
                            <option value="">— Pilih Provinsi —</option>
                            @foreach ($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Level 2: Kota/Kabupaten --}}
                    <div class="col-md-6">
                        <label for="select_kota" class="form-label fw-semibold">
                            <span class="badge bg-secondary rounded-pill me-1">2</span>
                            Kota / Kabupaten
                        </label>
                        <select id="select_kota" class="form-select form-select-lg wilayah-select" disabled>
                            <option value="">— Pilih Kota/Kabupaten —</option>
                        </select>
                        <div id="loading_kota" class="text-muted small mt-1 d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span> Memuat data...
                        </div>
                    </div>

                    {{-- Level 3: Kecamatan --}}
                    <div class="col-md-6">
                        <label for="select_kecamatan" class="form-label fw-semibold">
                            <span class="badge bg-secondary rounded-pill me-1">3</span>
                            Kecamatan
                        </label>
                        <select id="select_kecamatan" class="form-select form-select-lg wilayah-select" disabled>
                            <option value="">— Pilih Kecamatan —</option>
                        </select>
                        <div id="loading_kecamatan" class="text-muted small mt-1 d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span> Memuat data...
                        </div>
                    </div>

                    {{-- Level 4: Kelurahan --}}
                    <div class="col-md-6">
                        <label for="select_kelurahan" class="form-label fw-semibold">
                            <span class="badge bg-secondary rounded-pill me-1">4</span>
                            Kelurahan / Desa
                        </label>
                        <select id="select_kelurahan" class="form-select form-select-lg wilayah-select" disabled>
                            <option value="">— Pilih Kelurahan/Desa —</option>
                        </select>
                        <div id="loading_kelurahan" class="text-muted small mt-1 d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span> Memuat data...
                        </div>
                    </div>

                </div>{{-- end row --}}

                {{-- Hasil Pilihan --}}
                <div class="mt-4" id="hasil_section" style="display:none;">
                    <hr>
                    <h6 class="fw-bold text-success mb-3">
                        <i class="mdi mdi-check-circle me-1"></i> Hasil Pilihan
                    </h6>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="p-3 rounded bg-light border">
                                <small class="text-muted d-block">Provinsi</small>
                                <strong id="hasil_provinsi" class="text-dark">-</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded bg-light border">
                                <small class="text-muted d-block">Kota/Kabupaten</small>
                                <strong id="hasil_kota" class="text-dark">-</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded bg-light border">
                                <small class="text-muted d-block">Kecamatan</small>
                                <strong id="hasil_kecamatan" class="text-dark">-</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 rounded bg-light border">
                                <small class="text-muted d-block">Kelurahan/Desa</small>
                                <strong id="hasil_kelurahan" class="text-dark">-</strong>
                            </div>
                        </div>
                    </div>
                </div>

                    </div>{{-- end card-body --}}
                </div>{{-- end card --}}
            </div>
        </div>

    </div>
</div>
@endsection

@push('js-page')
{{-- jQuery sudah ada di vendor.bundle.base.js --}}
<script>
$(document).ready(function () {

    // ──────────────────────────────────────────
    // Helper: reset & disable select
    // ──────────────────────────────────────────
    function resetSelect(selectId, placeholder) {
        $('#' + selectId)
            .html('<option value="">' + placeholder + '</option>')
            .prop('disabled', true);
    }

    function updateHasil() {
        var prov = $('#select_provinsi option:selected').text();
        var kota = $('#select_kota option:selected').text();
        var kec  = $('#select_kecamatan option:selected').text();
        var kel  = $('#select_kelurahan option:selected').text();

        var anySelected = (
            $('#select_provinsi').val() ||
            $('#select_kota').val() ||
            $('#select_kecamatan').val() ||
            $('#select_kelurahan').val()
        );

        if (anySelected) {
            $('#hasil_section').show();
            $('#hasil_provinsi').text(prov !== '— Pilih Provinsi —' ? prov : '-');
            $('#hasil_kota').text(kota !== '— Pilih Kota/Kabupaten —' ? kota : '-');
            $('#hasil_kecamatan').text(kec !== '— Pilih Kecamatan —' ? kec : '-');
            $('#hasil_kelurahan').text(kel !== '— Pilih Kelurahan/Desa —' ? kel : '-');
        } else {
            $('#hasil_section').hide();
        }
    }

    // ──────────────────────────────────────────
    // Event: Provinsi berubah
    // ──────────────────────────────────────────
    $('#select_provinsi').on('change', function () {
        var province_id = $(this).val();

        // Reset level bawah
        resetSelect('select_kota', '— Pilih Kota/Kabupaten —');
        resetSelect('select_kecamatan', '— Pilih Kecamatan —');
        resetSelect('select_kelurahan', '— Pilih Kelurahan/Desa —');
        updateHasil();

        if (!province_id) return;

        // Tampilkan loading
        $('#loading_kota').removeClass('d-none');

        $.ajax({
            url: '/api/wilayah/regencies/' + province_id,
            type: 'GET',
            success: function (response) {
                $('#loading_kota').addClass('d-none');

                if (response.status === 'success') {
                    var options = '<option value="">— Pilih Kota/Kabupaten —</option>';
                    $.each(response.data, function (i, kota) {
                        options += '<option value="' + kota.id + '">' + kota.name + '</option>';
                    });
                    $('#select_kota').html(options).prop('disabled', false);
                }
                updateHasil();
            },
            error: function (xhr) {
                $('#loading_kota').addClass('d-none');
                console.error('Error memuat kota:', xhr);
                alert('Gagal memuat data kota. Silakan coba lagi.');
            }
        });
    });

    // ──────────────────────────────────────────
    // Event: Kota berubah
    // ──────────────────────────────────────────
    $('#select_kota').on('change', function () {
        var regency_id = $(this).val();

        // Reset level bawah
        resetSelect('select_kecamatan', '— Pilih Kecamatan —');
        resetSelect('select_kelurahan', '— Pilih Kelurahan/Desa —');
        updateHasil();

        if (!regency_id) return;

        $('#loading_kecamatan').removeClass('d-none');

        $.ajax({
            url: '/api/wilayah/districts/' + regency_id,
            type: 'GET',
            success: function (response) {
                $('#loading_kecamatan').addClass('d-none');

                if (response.status === 'success') {
                    var options = '<option value="">— Pilih Kecamatan —</option>';
                    $.each(response.data, function (i, kec) {
                        options += '<option value="' + kec.id + '">' + kec.name + '</option>';
                    });
                    $('#select_kecamatan').html(options).prop('disabled', false);
                }
                updateHasil();
            },
            error: function (xhr) {
                $('#loading_kecamatan').addClass('d-none');
                console.error('Error memuat kecamatan:', xhr);
                alert('Gagal memuat data kecamatan. Silakan coba lagi.');
            }
        });
    });

    // ──────────────────────────────────────────
    // Event: Kecamatan berubah
    // ──────────────────────────────────────────
    $('#select_kecamatan').on('change', function () {
        var district_id = $(this).val();

        // Reset level bawah
        resetSelect('select_kelurahan', '— Pilih Kelurahan/Desa —');
        updateHasil();

        if (!district_id) return;

        $('#loading_kelurahan').removeClass('d-none');

        $.ajax({
            url: '/api/wilayah/villages/' + district_id,
            type: 'GET',
            success: function (response) {
                $('#loading_kelurahan').addClass('d-none');

                if (response.status === 'success') {
                    var options = '<option value="">— Pilih Kelurahan/Desa —</option>';
                    $.each(response.data, function (i, kel) {
                        options += '<option value="' + kel.id + '">' + kel.name + '</option>';
                    });
                    $('#select_kelurahan').html(options).prop('disabled', false);
                }
                updateHasil();
            },
            error: function (xhr) {
                $('#loading_kelurahan').addClass('d-none');
                console.error('Error memuat kelurahan:', xhr);
                alert('Gagal memuat data kelurahan. Silakan coba lagi.');
            }
        });
    });

    // ──────────────────────────────────────────
    // Event: Kelurahan berubah → update hasil
    // ──────────────────────────────────────────
    $('#select_kelurahan').on('change', function () {
        updateHasil();
    });

});
</script>

<style>
.wilayah-select {
    border-radius: 8px;
    transition: all 0.2s ease;
}
.wilayah-select:focus {
    border-color: #4B49AC;
    box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.2);
}
.wilayah-select:disabled {
    background-color: #f8f9fa;
    cursor: not-allowed;
    opacity: 0.7;
}
#hasil_section .bg-light {
    transition: all 0.3s ease;
}
</style>
@endpush
