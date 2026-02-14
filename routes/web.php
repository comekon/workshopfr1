<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Models\Kategori;
use App\Models\Buku;

Auth::routes();



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

