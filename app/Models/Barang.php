<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id_barang';

    public $incrementing = false;   // 🔥 karena bukan auto increment

    protected $keyType = 'string';  // 🔥 karena BRG001 adalah string

    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'harga'
    ];

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_barang', 'id_barang');
    }
}