<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    protected $guarded = [];

    public function dokumenPengeluaran()
    {
        return $this->belongsTo(DokumenPengeluaran::class, 'dokumen_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
