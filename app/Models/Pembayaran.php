<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $guarded = [];

    public function dokumenPengeluaran()
    {
        return $this->belongsTo(DokumenPengeluaran::class, 'dokumen_id');
    }

    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
}
