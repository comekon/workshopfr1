<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegRegency extends Model
{
    protected $table = 'reg_regencies';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'province_id', 'name'];

    public function province()
    {
        return $this->belongsTo(RegProvince::class, 'province_id');
    }

    public function districts()
    {
        return $this->hasMany(RegDistrict::class, 'regency_id');
    }
}
