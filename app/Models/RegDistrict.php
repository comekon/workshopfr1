<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegDistrict extends Model
{
    protected $table = 'reg_districts';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'regency_id', 'name'];

    public function regency()
    {
        return $this->belongsTo(RegRegency::class, 'regency_id');
    }

    public function villages()
    {
        return $this->hasMany(RegVillage::class, 'district_id');
    }
}
