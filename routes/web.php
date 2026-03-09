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