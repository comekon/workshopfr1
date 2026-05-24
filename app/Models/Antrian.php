<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table = 'antrian';

    protected $fillable = [
        'nomor_antrian',
        'nama',
        'status',
        'called_at',
    ];

    protected $casts = [
        'called_at' => 'datetime',
    ];

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu')->orderBy('nomor_antrian');
    }

    public function scopeDipanggil($query)
    {
        return $query->where('status', 'dipanggil')->orderByDesc('called_at');
    }

    public function scopeTerlewat($query)
    {
        return $query->where('status', 'terlewat')->orderBy('nomor_antrian');
    }

    public static function generateNomorAntrian(): int
    {
        $last = static::orderByDesc('nomor_antrian')->value('nomor_antrian');
        return ($last ?? 0) + 1;
    }
}
