<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKoreksi extends Model
{
    protected $guarded = [];

    public function dokumenPengeluaran()
    {
        return $this->belongsTo(DokumenPengeluaran::class, 'dokumen_id');
    }

    public function jenisKesalahan()
    {
        return $this->belongsTo(JenisKesalahan::class, 'jenis_kesalahan_id');
    }

    public function pengoreksi()
    {
        return $this->belongsTo(User::class, 'dikoreksi_oleh');
    }
}
