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

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {

    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->email],
        [
            'nama' => $googleUser->name,
            'id_google' => $googleUser->id,
            'password' => bcrypt('google_login')
        ]
    );

    
    $otp = rand(100000, 999999);

    $user->update([
        'otp' => $otp,
        'otp_expired_at' => now()->addMinutes(5)
    ]);

    
    \Mail::raw("Kode OTP Login Kamu: $otp", function($message) use ($user){
        $message->to($user->email)
                ->subject('Kode OTP Login');
    });

    
    session(['otp_user_id' => $user->id]);

    
    return redirect('/otp');
});




Route::get('/otp', function () {
    return view('auth.otp');
});

Route::post('/otp', function (Illuminate\Http\Request $request) {

    $user = App\Models\User::find(session('otp_user_id'));

    if (!$user) return redirect('/login');

    if ($request->otp == $user->otp) {

        Auth::login($user);

        $user->update([
            'otp' => null
        ]);

        return redirect('/');
    }

    return back()->withErrors(['otp' => 'OTP salah']);
});





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