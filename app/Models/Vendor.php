<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';

    protected $fillable = [
        'nama_vendor',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'idvendor', 'idvendor');
    }
}
