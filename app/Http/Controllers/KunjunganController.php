<?php

namespace App\Http\Controllers;

use App\Models\LokasiToko;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KunjunganController extends Controller
{
    public function index()
    {
        $toko = LokasiToko::all();
        return view('kunjungan.index', compact('toko'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode'    => 'required|string|max:8|unique:lokasi_toko,barcode',
            'nama_toko'  => 'required|string|max:50',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'accuracy'   => 'required|numeric',
        ]);

        LokasiToko::create($request->only('barcode', 'nama_toko', 'latitude', 'longitude', 'accuracy'));

        return redirect()->route('kunjungan.index')
            ->with('success', 'Toko berhasil ditambahkan');
    }

    public function update(Request $request, $barcode)
    {
        $request->validate([
            'nama_toko'  => 'required|string|max:50',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'accuracy'   => 'required|numeric',
        ]);

        $toko = LokasiToko::findOrFail($barcode);
        $toko->update($request->only('nama_toko', 'latitude', 'longitude', 'accuracy'));

        return redirect()->route('kunjungan.index')
            ->with('success', 'Toko berhasil diupdate');
    }

    public function destroy($barcode)
    {
        LokasiToko::findOrFail($barcode)->delete();
        return redirect()->route('kunjungan.index')
            ->with('success', 'Toko berhasil dihapus');
    }

    public function cetakLabel(Request $request)
    {
        $request->validate([
            'barcodes'   => 'required|array|min:1',
            'start_x'    => 'required|integer|min:1|max:5',
            'start_y'    => 'required|integer|min:1|max:8',
        ]);

        $toko = LokasiToko::whereIn('barcode', $request->barcodes)->get();
        $startIndex = (($request->start_y - 1) * 5) + ($request->start_x - 1);
        $labels = array_fill(0, 40, null);

        foreach ($toko as $i => $item) {
            if (($startIndex + $i) < 40) {
                $labels[$startIndex + $i] = $item;
            }
        }

        $pdf = Pdf::loadView('kunjungan.cetak', compact('labels'));
        return $pdf->stream('label-toko.pdf');
    }

    public function apiCariToko($barcode)
    {
        $toko = LokasiToko::find($barcode);

        if (!$toko) {
            return response()->json(['status' => 'error', 'message' => 'Toko tidak ditemukan'], 404);
        }

        return response()->json([
            'status'    => 'success',
            'barcode'   => $toko->barcode,
            'nama_toko' => $toko->nama_toko,
            'latitude'  => $toko->latitude,
            'longitude' => $toko->longitude,
            'accuracy'  => $toko->accuracy,
        ]);
    }
}
