<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\KartuNfc;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AbsensiController extends Controller
{
    /**
     * Halaman scanner NFC
     */
    public function index()
    {
        return view('absensi.scanner');
    }

    /**
     * Endpoint API — terima UID, simpan absensi
     */
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'uid' => 'required|string',
            'mata_kuliah' => 'required|string',
            'pertemuan' => 'required|integer|min:1',
        ]);

        $kartu = KartuNfc::where('uid', $request->uid)->first();

        if (!$kartu) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak terdaftar',
            ], 404);
        }

        // Cek duplikat
        $sudahAbsen = Absensi::where('mahasiswa_id', $kartu->mahasiswa_id)
            ->where('mata_kuliah', $request->mata_kuliah)
            ->where('pertemuan', $request->pertemuan)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Sudah absen di pertemuan ini',
            ]);
        }

        $absensi = Absensi::create([
            'mahasiswa_id' => $kartu->mahasiswa_id,
            'mata_kuliah' => $request->mata_kuliah,
            'pertemuan' => $request->pertemuan,
            'waktu_scan' => now(),
            'status' => 'hadir',
        ]);

        $mahasiswa = $kartu->mahasiswa;

        return response()->json([
            'success' => true,
            'message' => 'Absen berhasil',
            'mahasiswa' => [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
            ],
        ]);
    }

    /**
     * Halaman admin kelola data
     */
    public function admin()
    {
        $mahasiswas = Mahasiswa::with('kartuNfc')->get();
        $kartus = KartuNfc::with('mahasiswa')->get();

        return view('absensi.admin', compact('mahasiswas', 'kartus'));
    }

    /**
     * Simpan data mahasiswa baru
     */
    public function storeMahasiswa(Request $request): JsonResponse
    {
        $request->validate([
            'nim' => 'required|string|unique:mahasiswa,nim',
            'nama' => 'required|string',
            'kelas' => 'nullable|string',
        ]);

        $mahasiswa = Mahasiswa::create($request->only('nim', 'nama', 'kelas'));

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil ditambahkan',
            'data' => $mahasiswa,
        ]);
    }

    /**
     * Update data mahasiswa
     */
    public function updateMahasiswa(Request $request, $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $request->validate([
            'nim' => 'required|string|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama' => 'required|string',
            'kelas' => 'nullable|string',
        ]);

        $mahasiswa->update($request->only('nim', 'nama', 'kelas'));

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil diupdate',
            'data' => $mahasiswa,
        ]);
    }

    /**
     * Hapus mahasiswa
     */
    public function destroyMahasiswa($id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil dihapus',
        ]);
    }

    /**
     * Daftarkan kartu NFC baru ke mahasiswa
     */
    public function storeKartu(Request $request): JsonResponse
    {
        $request->validate([
            'uid' => 'required|string|unique:kartu_nfc,uid',
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'label' => 'nullable|string',
        ]);

        // Cek apakah mahasiswa sudah punya kartu
        $existing = KartuNfc::where('mahasiswa_id', $request->mahasiswa_id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa sudah memiliki kartu terdaftar',
            ], 422);
        }

        $kartu = KartuNfc::create($request->only('uid', 'mahasiswa_id', 'label'));
        $kartu->load('mahasiswa');

        return response()->json([
            'success' => true,
            'message' => 'Kartu NFC berhasil didaftarkan',
            'data' => $kartu,
        ]);
    }

    /**
     * Hapus kartu NFC
     */
    public function destroyKartu($id): JsonResponse
    {
        $kartu = KartuNfc::findOrFail($id);
        $kartu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kartu NFC berhasil dihapus',
        ]);
    }

    /**
     * Data rekap kehadiran (JSON)
     */
    public function rekap(Request $request): JsonResponse
    {
        $request->validate([
            'mata_kuliah' => 'required|string',
            'pertemuan' => 'required|integer|min:1',
        ]);

        $mahasiswas = Mahasiswa::all();

        $absensi = Absensi::where('mata_kuliah', $request->mata_kuliah)
            ->where('pertemuan', $request->pertemuan)
            ->get()
            ->keyBy('mahasiswa_id');

        $rekap = $mahasiswas->map(function ($mhs) use ($absensi) {
            $record = $absensi->get($mhs->id);
            return [
                'nim' => $mhs->nim,
                'nama' => $mhs->nama,
                'status' => $record ? 'hadir' : 'tidak',
                'waktu_scan' => $record?->waktu_scan?->format('H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $rekap,
        ]);
    }
}
