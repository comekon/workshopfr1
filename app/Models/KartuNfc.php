<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KartuNfc extends Model
{
    protected $table = 'kartu_nfc';

    protected $fillable = ['uid', 'mahasiswa_id', 'label'];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
