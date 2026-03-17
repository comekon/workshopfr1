@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">

        {{-- Header --}}
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-cash-register"></i>
                </span> Point of Sales (POS)
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Modul 5</li>
                    <li class="breadcrumb-item active" aria-current="page">POS (AJAX)</li>
                </ul>
            </nav>
        </div>

        <div class="row">
            {{-- ── Form Input Barang ─────────────────────── --}}
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Input Barang</h4>

                        <div class="mb-3">
                            <label for="kode_barang" class="form-label fw-semibold">Kode Barang</label>
                            <div class="input-group">
                                <input type="text" id="kode_barang" class="form-control"
                                       placeholder="Contoh: BRG001" autocomplete="off"
                                       style="text-transform:uppercase">
                                <span class="input-group-text" id="icon_kode">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                            </div>
                            <div id="info_barang" class="form-text mt-1"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Barang</label>
                            <input type="text" id="nama_barang" class="form-control bg-light" readonly
                                   placeholder="Otomatis terisi">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Satuan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="harga_barang" class="form-control bg-light" readonly
                                       placeholder="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number" id="jumlah_barang" class="form-control" value="1" min="1">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" id="btn_tambah" class="btn btn-gradient-primary btn-lg" disabled
                                    onclick="tambahKeCart()">
                                <i class="mdi mdi-plus-circle"></i> Tambahkan
                            </button>
                        </div>

                        <div class="alert alert-light border mt-3 mb-0 small">
                            <strong>Kode test:</strong> BRG001 s/d BRG012
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Tabel Keranjang ───────────────────────── --}}
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body flex-column d-flex">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Keranjang Belanja</h4>
                            <span id="badge_item" class="badge badge-primary badge-pill">0 item</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="text-center bg-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th width="15%">Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_cart">
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="mdi mdi-cart-outline fs-3 d-block mb-1"></i>
                                            Keranjang masih kosong
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-auto pt-4 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-dark">
                                    <h4 class="mb-0">Total: <span id="total_display" class="font-weight-bold text-primary ms-2">Rp 0</span></h4>
                                </div>
                                <button type="button" id="btn_bayar" class="btn btn-gradient-success btn-lg" disabled
                                        onclick="prosesPayment()">
                                    <i class="mdi mdi-cash-check"></i> Bayar
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js-page')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ═══════════════════════════════════════════════
// STATE — cart disimpan di memori JS
// ═══════════════════════════════════════════════
var cart = [];
var barangAktif = null;   // data barang yang sedang ditampilkan di form

function formatRp(angka) {
    return new Intl.NumberFormat('id-ID').format(angka);
}

// ═══════════════════════════════════════════════
// EVENT: tekan Enter pada input kode barang
// ═══════════════════════════════════════════════
$('#kode_barang').on('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        var kode = $(this).val().trim().toUpperCase();
        if (kode) cariBarang(kode);
    }
});

// ═══════════════════════════════════════════════
// AJAX: cari barang ke server
// ═══════════════════════════════════════════════
function cariBarang(kode) {
    barangAktif = null;
    $('#btn_tambah').prop('disabled', true);
    $('#nama_barang, #harga_barang').val('');
    $('#info_barang').html('');
    $('#icon_kode').html('<span class="spinner-border spinner-border-sm text-primary"></span>');

    $.ajax({
        url: '/api/pos/barang/' + kode,
        type: 'GET',
        success: function(res) {
            barangAktif = res.data;
            $('#icon_kode').html('<i class="mdi mdi-check-circle text-success"></i>');
            $('#nama_barang').val(res.data.nama_barang);
            $('#harga_barang').val(formatRp(res.data.harga));
            $('#jumlah_barang').val(1);
            $('#info_barang').html('<span class="text-success"><i class="mdi mdi-check"></i> Barang ditemukan</span>');
            $('#btn_tambah').prop('disabled', false);
            $('#jumlah_barang').focus();
        },
        error: function() {
            $('#icon_kode').html('<i class="mdi mdi-close-circle text-danger"></i>');
            $('#info_barang').html('<span class="text-danger"><i class="mdi mdi-alert"></i> Barang tidak ditemukan</span>');
        }
    });
}

// ═══════════════════════════════════════════════
// Tambahkan barang ke cart
// ═══════════════════════════════════════════════
function tambahKeCart() {
    if (!barangAktif) return;

    var jumlah = parseInt($('#jumlah_barang').val());
    if (!jumlah || jumlah < 1) {
        Swal.fire('Perhatian', 'Jumlah harus lebih dari 0.', 'warning');
        return;
    }

    var id  = barangAktif.id_barang;
    var idx = cart.findIndex(function(i) { return i.id_barang === id; });

    if (idx >= 0) {
        // kode sama → update jumlah & subtotal
        cart[idx].jumlah   += jumlah;
        cart[idx].subtotal  = cart[idx].jumlah * cart[idx].harga;
    } else {
        cart.push({
            id_barang   : id,
            nama_barang : barangAktif.nama_barang,
            harga       : barangAktif.harga,
            jumlah      : jumlah,
            subtotal    : barangAktif.harga * jumlah,
        });
    }

    renderCart();
    resetForm();
}

// ═══════════════════════════════════════════════
// Render ulang tabel & total
// ═══════════════════════════════════════════════
function renderCart() {
    var tbody = $('#tbody_cart');
    tbody.empty();

    if (cart.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center text-muted py-4">' +
            '<i class="mdi mdi-cart-outline fs-3 d-block mb-1"></i>Keranjang masih kosong</td></tr>');
        $('#btn_bayar').prop('disabled', true);
        $('#badge_item').text('0 item');
        $('#total_display').text('Rp 0');
        return;
    }

    var total = 0;
    cart.forEach(function(item, idx) {
        total += item.subtotal;
        tbody.append(
            '<tr>' +
            '<td>' + (idx + 1) + '</td>' +
            '<td><code>' + item.id_barang + '</code></td>' +
            '<td>' + item.nama_barang + '</td>' +
            '<td>Rp ' + formatRp(item.harga) + '</td>' +
            '<td><input type="number" class="form-control form-control-sm" style="width:70px" ' +
                'min="1" value="' + item.jumlah + '" onchange="ubahJumlah(' + idx + ',this.value)"></td>' +
            '<td class="fw-bold text-primary">Rp ' + formatRp(item.subtotal) + '</td>' +
            '<td><button class="btn btn-danger btn-sm" onclick="hapusItem(' + idx + ')">' +
                '<i class="mdi mdi-delete"></i></button></td>' +
            '</tr>'
        );
    });

    $('#total_display').text('Rp ' + formatRp(total));
    $('#badge_item').text(cart.length + ' item');
    $('#btn_bayar').prop('disabled', false);
}

function ubahJumlah(idx, val) {
    var j = parseInt(val) || 1;
    cart[idx].jumlah   = j;
    cart[idx].subtotal = cart[idx].harga * j;
    renderCart();
}

function hapusItem(idx) {
    cart.splice(idx, 1);
    renderCart();
}

function resetForm() {
    barangAktif = null;
    $('#kode_barang').val('').focus();
    $('#nama_barang, #harga_barang').val('');
    $('#jumlah_barang').val(1);
    $('#info_barang').html('');
    $('#icon_kode').html('<i class="mdi mdi-magnify"></i>');
    $('#btn_tambah').prop('disabled', true);
}

// ═══════════════════════════════════════════════
// AJAX POST: Bayar → simpan ke DB
// ═══════════════════════════════════════════════
function prosesPayment() {
    if (!cart.length) return;

    var total = cart.reduce(function(s, i) { return s + i.subtotal; }, 0);

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: 'Total: <strong>Rp ' + formatRp(total) + '</strong><br>Lanjutkan?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Bayar!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#198754',
    }).then(function(result) {
        if (!result.isConfirmed) return;

        $('#btn_bayar')
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...')
            .prop('disabled', true);

        $.ajax({
            url: '/api/pos/bayar',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                _token : '{{ csrf_token() }}',
                cart   : cart,
                total  : total,
            }),
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire({
                        title: '✅ Pembayaran Berhasil!',
                        html: 'No. Transaksi: <strong>#' + res.id_penjualan + '</strong><br>' +
                              'Total: <strong>Rp ' + formatRp(res.total) + '</strong><br>' +
                              res.jumlah_item + ' item terjual',
                        icon: 'success',
                        confirmButtonColor: '#198754',
                    });
                    
                    $('#btn_bayar').html('<i class="mdi mdi-cash-check me-1"></i> Bayar');
                    cart = [];
                    renderCart();
                    resetForm();
                }
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message : 'Terjadi kesalahan server.';
                Swal.fire('Gagal!', msg, 'error');
                $('#btn_bayar')
                    .html('<i class="mdi mdi-cash-check me-1"></i> Bayar')
                    .prop('disabled', false);
            }
        });
    });
}
</script>
@endpush
