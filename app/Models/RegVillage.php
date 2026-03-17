<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegVillage extends Model
{
    protected $table = 'reg_villages';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'district_id', 'name'];

    public function district()
    {
        return $this->belongsTo(RegDistrict::class, 'district_id');
    }
}
