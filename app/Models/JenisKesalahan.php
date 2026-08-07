<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKesalahan extends Model
{
    protected $guarded = [];

    public function riwayatKoreksi()
    {
        return $this->hasMany(RiwayatKoreksi::class, 'jenis_kesalahan_id');
    }
}
