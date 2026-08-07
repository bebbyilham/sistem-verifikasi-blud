<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPengeluaran extends Model
{
    protected $guarded = [];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function pptk()
    {
        return $this->belongsTo(User::class, 'pptk_id');
    }

    public function verifikasi()
    {
        return $this->hasMany(Verifikasi::class, 'dokumen_id');
    }

    public function riwayatKoreksi()
    {
        return $this->hasMany(RiwayatKoreksi::class, 'dokumen_id');
    }

    public function pengesahan()
    {
        return $this->hasOne(Pengesahan::class, 'dokumen_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'dokumen_id');
    }
}
