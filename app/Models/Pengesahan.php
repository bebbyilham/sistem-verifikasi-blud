<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengesahan extends Model
{
    protected $guarded = [];

    public function dokumenPengeluaran()
    {
        return $this->belongsTo(DokumenPengeluaran::class, 'dokumen_id');
    }

    public function ppk()
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }
}
