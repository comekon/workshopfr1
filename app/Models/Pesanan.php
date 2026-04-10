<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'timestamp',
        'total',
        'metode_bayar',
        'status_bayar',
        'snap_token',
        'midtrans_order_id',
    ];

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
}
