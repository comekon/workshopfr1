<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function index()
    {
        $sertifikat = \App\Models\User::all(); // contoh ambil user

        return view('pdf.sertifikat.index', compact('sertifikat'));
    }
    public function preview($id)
    {
        $user = \App\Models\User::findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sertifikat.preview', [
            'nama' => $user->nama
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('sertifikat.pdf');
    }
    public function download($id)
    {
        $user = \App\Models\User::findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sertifikat', [
            'nama' => $user->nama
        ])->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat.pdf');
    }

    public function pengumumanIndex()
    {
        $pengumuman = \App\Models\User::all();

        return view('pdf.pengumuman.index', compact('pengumuman'));
    }

    public function pengumumanPreview($id)
    {
        $user = \App\Models\User::findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.pengumuman.preview', [
            'nama' => $user->nama
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('pengumuman.pdf');
    }

    public function pengumumanDownload($id)
    {
        $user = \App\Models\User::findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.pengumuman', [
            'nama' => $user->nama
        ])->setPaper('a4', 'portrait');

        return $pdf->download('pengumuman.pdf');
    }
}