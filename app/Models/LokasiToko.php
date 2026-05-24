<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiToko extends Model
{
    protected $table = 'lokasi_toko';
    protected $primaryKey = 'barcode';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'barcode',
        'nama_toko',
        'latitude',
        'longitude',
        'accuracy',
    ];
}
