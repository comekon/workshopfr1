<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::get();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|numeric'
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil ditambahkan');
    }
    public function show(Barang $barang)
    {
        return redirect()->route('barang.index');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga' => 'required|numeric'
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function cetakLabel(Request $request)
    {
        $request->validate([
            'barang_ids' => 'required|array|min:1',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8',
        ]);

        $barang = Barang::whereIn('id_barang', $request->barang_ids)->get();

        $startIndex = (($request->start_y - 1) * 5) + ($request->start_x - 1);

        $labels = array_fill(0, 40, null);

        foreach ($barang as $i => $item) {
            if (($startIndex + $i) < 40) {
                $labels[$startIndex + $i] = $item;
            }
        }

        $pdf = Pdf::loadView('barang.cetak', compact('labels'));

        return $pdf->stream('label-barang.pdf');
    }
}