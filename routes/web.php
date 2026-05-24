<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Kategori;
use App\Models\Buku;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\KantinController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\AntrianController;


Route::get('/barang/scan', [BarangController::class, 'scan'])
    ->name('barang.scan');

Route::get('/api/barang/{id}', [BarangController::class, 'getBarang'])
    ->name('api.barang.show');

Route::post('/barang/cetak', [BarangController::class, 'cetakLabel'])
    ->name('barang.cetak');

Route::middleware(['auth'])->group(function () {

    Route::resource('barang', BarangController::class)
        ->except(['show']);

});


//sertif
Route::get('/sertifikat', [PdfController::class, 'index'])
        ->name('pdf.sertifikat.index');

Route::get('/sertifikat/{id}/preview', [PdfController::class, 'preview'])
        ->name('pdf.sertifikat.preview');

Route::get('/sertifikat/{id}/download', [PdfController::class, 'download'])
        ->name('pdf.sertifikat.download');

// pengumuman
Route::get('/pengumuman', [PdfController::class, 'pengumumanIndex'])
        ->name('pdf.pengumuman.index');

Route::get('/pengumuman/{id}/preview', [PdfController::class, 'pengumumanPreview'])
        ->name('pdf.pengumuman.preview');

Route::get('/pengumuman/{id}/download', [PdfController::class, 'pengumumanDownload'])
        ->name('pdf.pengumuman.download');


Auth::routes();


Route::post('/login', [LoginController::class, 'login'])->name('login');



/*
login google
*/

Route::get('/auth/google', [GoogleLoginController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleLoginController::class, 'callback']);

Route::get('/otp', [GoogleLoginController::class, 'otpIndex']);
Route::post('/otp', [GoogleLoginController::class, 'otpVerify']);





Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {

        $totalKategori = Kategori::count();
        $totalBuku = Buku::count();
        $bukuTerbaru = Buku::with('kategori')
                            ->latest('idbuku')
                            ->take(5)
                            ->get();

        return view('dashboard', compact(
            'totalKategori',
            'totalBuku',
            'bukuTerbaru'
        ));

    })->name('dashboard');

    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

});


Route::post('/tes-mutlak', function () {
    dd('ROUTE NORMAL! Server dan Laravel aman.');
});

// Modul 4 Routes
Route::get('/modul4/html-table', function () {
    return view('modul4.html-table');
})->name('modul4.html-table');

Route::get('/modul4/datatables', function () {
    return view('modul4.datatables');
})->name('modul4.datatables');

Route::get('/modul4/select', function () {
    return view('modul4.select');
})->name('modul4.select');

// =============================================
// Modul 5 - AJAX jQuery & Axios
// =============================================

// Halaman Wilayah
Route::get('/modul5/wilayah/ajax', [WilayahController::class, 'indexAjax'])
    ->name('modul5.wilayah.ajax');

Route::get('/modul5/wilayah/axios', [WilayahController::class, 'indexAxios'])
    ->name('modul5.wilayah.axios');

// API Endpoints Wilayah (dikonsumsi AJAX / Axios dari frontend)
Route::get('/api/wilayah/regencies/{province_id}', [WilayahController::class, 'getRegencies'])
    ->name('api.wilayah.regencies');

Route::get('/api/wilayah/districts/{regency_id}', [WilayahController::class, 'getDistricts'])
    ->name('api.wilayah.districts');

Route::get('/api/wilayah/villages/{district_id}', [WilayahController::class, 'getVillages'])
    ->name('api.wilayah.villages');

// =============================================
// Modul 5 - POS (Point of Sales)
// =============================================

Route::get('/modul5/pos/ajax', [PosController::class, 'indexAjax'])
    ->name('modul5.pos.ajax');

Route::get('/modul5/pos/axios', [PosController::class, 'indexAxios'])
    ->name('modul5.pos.axios');

// API POS
Route::get('/api/pos/barang/{kode}', [PosController::class, 'cariBarang'])
    ->name('api.pos.barang');

Route::post('/api/pos/bayar', [PosController::class, 'bayar'])
    ->name('api.pos.bayar');

// =============================================
// Kantin Online (Customer — Tanpa Login)
// =============================================
Route::get('/kantin', [KantinController::class, 'index'])
    ->name('kantin.index');

Route::get('/api/kantin/menu/{vendor_id}', [KantinController::class, 'getMenuByVendor'])
    ->name('api.kantin.menu');

Route::post('/api/kantin/checkout', [KantinController::class, 'checkout'])
    ->name('api.kantin.checkout');

Route::post('/api/kantin/notification', [KantinController::class, 'handleNotification'])
    ->name('api.kantin.notification');

Route::get('/kantin/success/{order_id}', [KantinController::class, 'paymentSuccess'])
    ->name('kantin.success');

Route::get('/kantin/qr/{idpesanan}', [KantinController::class, 'qrPage'])
    ->name('kantin.qr');

Route::get('/api/kantin/pesanan/{idpesanan}', [KantinController::class, 'getPesananByQr'])
    ->name('api.kantin.pesanan');

// =============================================
// Customer (Studi Kasus 3)
// =============================================
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/create1', [CustomerController::class, 'create1'])->name('create1');
    Route::post('/store1', [CustomerController::class, 'store1'])->name('store1');
    Route::get('/create2', [CustomerController::class, 'create2'])->name('create2');
    Route::post('/store2', [CustomerController::class, 'store2'])->name('store2');
    Route::get('/foto/{id}', [CustomerController::class, 'showFoto'])->name('foto');
});

// =============================================
// Vendor Panel (Login Required)
// =============================================
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::resource('menu', VendorController::class)->except(['show']);
    Route::get('/pesanan-lunas', [VendorController::class, 'pesananLunas'])->name('pesanan.lunas');
    Route::get('/scan-qr', [VendorController::class, 'scanQr'])->name('scan.qr');
});

// =============================================
// Kunjungan Toko (Geolocation)
// =============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('kunjungan.store');
    Route::put('/kunjungan/{barcode}', [KunjunganController::class, 'update'])->name('kunjungan.update');
    Route::delete('/kunjungan/{barcode}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');
    Route::post('/kunjungan/cetak', [KunjunganController::class, 'cetakLabel'])->name('kunjungan.cetak');
});

Route::get('/api/kunjungan/toko/{barcode}', [KunjunganController::class, 'apiCariToko'])
    ->name('api.kunjungan.toko');

// =============================================
// Sistem Antrian Real-Time (SSE)
// =============================================

// Public routes
Route::get('/guest', [AntrianController::class, 'index'])->name('antrian.guest');
Route::post('/antrian', [AntrianController::class, 'store'])->name('antrian.store');
Route::get('/antrian/{id}', [AntrianController::class, 'show'])->name('antrian.tiket');
Route::get('/papan', [AntrianController::class, 'papanView'])->name('antrian.papan');
Route::get('/sse/antrian', [AntrianController::class, 'streamSse'])->name('antrian.sse');

// Admin routes (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/antrian', [AntrianController::class, 'admin'])->name('antrian.admin');
    Route::post('/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil');
    Route::post('/antrian/{id}/selesai', [AntrianController::class, 'selesai'])->name('antrian.selesai');
    Route::post('/antrian/{id}/terlambat', [AntrianController::class, 'terlambat'])->name('antrian.terlambat');
});