@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">

        {{-- Header --}}
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-success text-white me-2">
                    <i class="mdi mdi-cash-register"></i>
                </span> Point of Sales (POS)
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item" aria-current="page">Modul 5</li>
                    <li class="breadcrumb-item active" aria-current="page">POS (Axios)</li>
                </ul>
            </nav>
        </div>

        <div class="row">
            {{-- ── Form Input Barang ─────────────────────── --}}
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-success">Input Barang</h4>

                        <div class="mb-3">
                            <label for="kode_barang" class="form-label fw-semibold">Kode Barang</label>
                            <div class="input-group">
                                <input type="text" id="kode_barang" class="form-control"
                                       placeholder="Contoh: BRG001" autocomplete="off"
                                       onkeydown="handleEnter(event)"
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
                            <button type="button" id="btn_tambah" class="btn btn-gradient-success btn-lg" disabled
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
                            <span id="badge_item" class="badge badge-success badge-pill">0 item</span>
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
                                    <h4 class="mb-0">Total: <span id="total_display" class="font-weight-bold text-success ms-2">Rp 0</span></h4>
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ═══════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════
var cart = [];
var barangAktif = null;

function formatRp(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

// ═══════════════════════════════════════════════
// EVENT: Enter pada input kode
// ═══════════════════════════════════════════════
function handleEnter(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        var kode = document.getElementById('kode_barang').value.trim().toUpperCase();
        if (kode) cariBarang(kode);
    }
}

// ═══════════════════════════════════════════════
// AXIOS GET: Cari barang → Promise .then/.catch
// ═══════════════════════════════════════════════
function cariBarang(kode) {
    barangAktif = null;
    document.getElementById('btn_tambah').disabled = true;
    document.getElementById('nama_barang').value   = '';
    document.getElementById('harga_barang').value  = '';
    document.getElementById('info_barang').innerHTML = '';
    document.getElementById('icon_kode').innerHTML =
        '<span class="spinner-border spinner-border-sm text-success"></span>';

    axios.get('/api/pos/barang/' + kode)
        .then(function(response) {
            // response.data  → JSON dari Laravel
            // response.data.data → objek barang
            var data = response.data.data;
            barangAktif = data;

            document.getElementById('icon_kode').innerHTML =
                '<i class="mdi mdi-check-circle text-success"></i>';
            document.getElementById('nama_barang').value  = data.nama_barang;
            document.getElementById('harga_barang').value = formatRp(data.harga);
            document.getElementById('jumlah_barang').value = 1;
            document.getElementById('info_barang').innerHTML =
                '<span class="text-success"><i class="mdi mdi-check"></i> Barang ditemukan</span>';
            document.getElementById('btn_tambah').disabled = false;
            document.getElementById('jumlah_barang').focus();
        })
        .catch(function() {
            document.getElementById('icon_kode').innerHTML =
                '<i class="mdi mdi-close-circle text-danger"></i>';
            document.getElementById('info_barang').innerHTML =
                '<span class="text-danger"><i class="mdi mdi-alert"></i> Barang tidak ditemukan</span>';
        });
}

// ═══════════════════════════════════════════════
// Tambahkan ke cart
// ═══════════════════════════════════════════════
function tambahKeCart() {
    if (!barangAktif) return;

    var jumlah = parseInt(document.getElementById('jumlah_barang').value);
    if (!jumlah || jumlah < 1) {
        Swal.fire('Perhatian', 'Jumlah harus lebih dari 0.', 'warning');
        return;
    }

    var id  = barangAktif.id_barang;
    var idx = cart.findIndex(function(i) { return i.id_barang === id; });

    if (idx >= 0) {
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
// Render tabel cart
// ═══════════════════════════════════════════════
function renderCart() {
    var tbody = document.getElementById('tbody_cart');
    tbody.innerHTML = '';

    if (cart.length === 0) {
        tbody.innerHTML =
            '<tr><td colspan="7" class="text-center text-muted py-4">' +
            '<i class="mdi mdi-cart-outline fs-3 d-block mb-1"></i>Keranjang masih kosong</td></tr>';
        document.getElementById('btn_bayar').disabled = true;
        document.getElementById('badge_item').textContent = '0 item';
        document.getElementById('total_display').textContent = 'Rp 0';
        return;
    }

    var total = 0;
    cart.forEach(function(item, idx) {
        total += item.subtotal;
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td>' + (idx + 1) + '</td>' +
            '<td><code>' + item.id_barang + '</code></td>' +
            '<td>' + item.nama_barang + '</td>' +
            '<td>Rp ' + formatRp(item.harga) + '</td>' +
            '<td><input type="number" class="form-control form-control-sm" style="width:70px" ' +
                'min="1" value="' + item.jumlah + '" onchange="ubahJumlah(' + idx + ',this.value)"></td>' +
            '<td class="fw-bold text-success">Rp ' + formatRp(item.subtotal) + '</td>' +
            '<td><button class="btn btn-danger btn-sm" onclick="hapusItem(' + idx + ')">' +
                '<i class="mdi mdi-delete"></i></button></td>';
        tbody.appendChild(tr);
    });

    document.getElementById('total_display').textContent = 'Rp ' + formatRp(total);
    document.getElementById('badge_item').textContent = cart.length + ' item';
    document.getElementById('btn_bayar').disabled = false;
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
    document.getElementById('kode_barang').value  = '';
    document.getElementById('nama_barang').value  = '';
    document.getElementById('harga_barang').value = '';
    document.getElementById('jumlah_barang').value = 1;
    document.getElementById('info_barang').innerHTML = '';
    document.getElementById('icon_kode').innerHTML = '<i class="mdi mdi-magnify"></i>';
    document.getElementById('btn_tambah').disabled = true;
    document.getElementById('kode_barang').focus();
}

// ═══════════════════════════════════════════════
// AXIOS POST: Bayar → Promise .then/.catch
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

        var btn = document.getElementById('btn_bayar');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
        btn.disabled  = true;

        // ── Axios POST dengan Promise ─────────────────────
        axios.post('/api/pos/bayar', {
            cart  : cart,
            total : total,
        }, {
            headers: {
                'X-CSRF-TOKEN' : '{{ csrf_token() }}',
                'Content-Type' : 'application/json',
            }
        })
        .then(function(response) {
            var res = response.data;
            Swal.fire({
                title: '✅ Pembayaran Berhasil!',
                html: 'No. Transaksi: <strong>#' + res.id_penjualan + '</strong><br>' +
                      'Total: <strong>Rp ' + formatRp(res.total) + '</strong><br>' +
                      res.jumlah_item + ' item terjual',
                icon: 'success',
                confirmButtonColor: '#198754',
            });
            
            btn.innerHTML = '<i class="mdi mdi-cash-check me-1"></i> Bayar';
            cart = [];
            renderCart();
            resetForm();
        })
        .catch(function(error) {
            var msg = (error.response && error.response.data)
                ? error.response.data.message : 'Terjadi kesalahan server.';
            Swal.fire('Gagal!', msg, 'error');
            btn.innerHTML = '<i class="mdi mdi-cash-check me-1"></i> Bayar';
            btn.disabled  = false;
        });
    });
}
</script>
@endpush
