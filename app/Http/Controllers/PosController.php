<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /** Halaman POS versi AJAX jQuery */
    public function indexAjax()
    {
        return view('modul5.pos-ajax');
    }

    /** Halaman POS versi Axios */
    public function indexAxios()
    {
        return view('modul5.pos-axios');
    }

    /**
     * API: Cari barang berdasarkan kode
     * GET /api/pos/barang/{kode}
     */
    public function cariBarang($kode)
    {
        $barang = Barang::find(strtoupper($kode));

        if (!$barang) {
            return response()->json([
                'status'  => 'not_found',
                'code'    => 404,
                'message' => 'Barang dengan kode ' . $kode . ' tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'code'   => 200,
            'data'   => [
                'id_barang'   => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,  // kolom aktual di DB
                'harga'       => $barang->harga,
            ],
        ]);
    }

    /**
     * API: Proses pembayaran — simpan ke database
     * POST /api/pos/bayar
     *
     * Body JSON: { cart: [...], total: 99000 }
     */
    public function bayar(Request $request)
    {
        $cart  = $request->input('cart', []);
        $total = $request->input('total', 0);

        if (empty($cart)) {
            return response()->json([
                'status'  => 'error',
                'code'    => 422,
                'message' => 'Keranjang belanja kosong.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Simpan header penjualan
            $penjualan = Penjualan::create([
                'total'     => $total,
                'timestamp' => now(),
            ]);

            // 2. Simpan setiap item detail
            foreach ($cart as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang'    => $item['id_barang'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'       => 'success',
                'code'         => 200,
                'message'      => 'Transaksi berhasil disimpan.',
                'id_penjualan' => $penjualan->id_penjualan,
                'total'        => $total,
                'jumlah_item'  => count($cart),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
