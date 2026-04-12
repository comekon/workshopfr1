<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Tampilkan data customer (Data Customer)
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Form Tambah Customer 1 - Foto sebagai BLOB
     */
    public function create1()
    {
        return view('customer.create1');
    }

    /**
     * Simpan Customer dengan foto BLOB (base64)
     */
    public function store1(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kodepos' => 'required',
            'foto' => 'required|string', // base64 string dari kamera
        ]);

        // Simpan base64 string langsung (tanpa decode untuk PostgreSQL compatibility)
        $fotoBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $request->foto);

        Customer::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos' => $request->kodepos,
            'foto_blob' => $fotoBase64,
            'foto_path' => null,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer dengan foto BLOB berhasil ditambahkan!');
    }

    /**
     * Form Tambah Customer 2 - Foto sebagai File
     */
    public function create2()
    {
        return view('customer.create2');
    }

    /**
     * Simpan Customer dengan foto File
     */
    public function store2(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kodepos' => 'required',
            'foto' => 'required|string', // base64 string dari kamera
        ]);

        // Decode base64 dan simpan sebagai file
        $fotoBase64 = $request->foto;
        $fotoData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64));

        $filename = 'customer_' . time() . '_' . uniqid() . '.png';
        $path = 'public/customers/' . $filename;

        Storage::put($path, $fotoData);

        Customer::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos' => $request->kodepos,
            'foto_blob' => null,
            'foto_path' => $path,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer dengan foto file berhasil ditambahkan!');
    }

    /**
     * Display foto BLOB dari database (decode base64)
     */
    public function showFoto($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->foto_blob) {
            $fotoBinary = base64_decode($customer->foto_blob);
            return response($fotoBinary)
                ->header('Content-Type', 'image/png');
        }

        if ($customer->foto_path) {
            return Storage::response($customer->foto_path);
        }

        abort(404);
    }
}
