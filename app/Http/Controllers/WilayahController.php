<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegProvince;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;

class WilayahController extends Controller
{
    /**
     * Halaman Wilayah versi AJAX jQuery
     */
    public function indexAjax()
    {
        $provinces = RegProvince::orderBy('name')->get();
        return view('modul5.wilayah-ajax', compact('provinces'));
    }

    /**
     * Halaman Wilayah versi Axios
     */
    public function indexAxios()
    {
        $provinces = RegProvince::orderBy('name')->get();
        return view('modul5.wilayah-axios', compact('provinces'));
    }

    /**
     * API: Ambil daftar Kota/Kabupaten berdasarkan Provinsi
     * GET /api/wilayah/regencies/{province_id}
     */
    public function getRegencies($province_id)
    {
        $regencies = RegRegency::where('province_id', $province_id)
                        ->orderBy('name')
                        ->get(['id', 'name']);

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'data'    => $regencies,
        ]);
    }

    /**
     * API: Ambil daftar Kecamatan berdasarkan Kota/Kabupaten
     * GET /api/wilayah/districts/{regency_id}
     */
    public function getDistricts($regency_id)
    {
        $districts = RegDistrict::where('regency_id', $regency_id)
                        ->orderBy('name')
                        ->get(['id', 'name']);

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'data'    => $districts,
        ]);
    }

    /**
     * API: Ambil daftar Kelurahan berdasarkan Kecamatan
     * GET /api/wilayah/villages/{district_id}
     */
    public function getVillages($district_id)
    {
        $villages = RegVillage::where('district_id', $district_id)
                        ->orderBy('name')
                        ->get(['id', 'name']);

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'data'    => $villages,
        ]);
    }
}
