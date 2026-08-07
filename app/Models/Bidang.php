<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function dokumenPengeluaran()
    {
        return $this->hasMany(DokumenPengeluaran::class);
    }
}
