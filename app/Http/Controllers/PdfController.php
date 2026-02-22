<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function sertifikat()
    {
        $data = [
            'nama' => 'Brillian Arhaburrizqi Kusuma'
        ];

        $pdf = Pdf::loadView('pdf.sertifikat', $data)
                    ->setPaper('a4', 'landscape');

        return $pdf->download('sertifikat.pdf');
    }

    public function pengumuman()
    {
        $pdf = Pdf::loadView('pdf.pengumuman')
                    ->setPaper('a4', 'portrait');

        return $pdf->download('pengumuman.pdf');
    }
}