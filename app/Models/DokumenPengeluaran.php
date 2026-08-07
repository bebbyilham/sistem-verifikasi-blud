<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPengeluaran extends Model
{
    protected $guarded = [];

    protected $casts = [
        'file_path' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->kode_dokumen)) {
                $lastDoc = static::latest('id')->first();
                $nextId = $lastDoc ? $lastDoc->id + 1 : 1;
                $model->kode_dokumen = 'BLUD-' . date('Y-m') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($model) {
            $verifikators = User::role('verifikator')->get();

            if ($verifikators->isNotEmpty()) {
                \Filament\Notifications\Notification::make()
                    ->title('Dokumen Baru Diajukan')
                    ->body('Dokumen ' . $model->kode_dokumen . ' telah diajukan oleh PPTK dan menunggu verifikasi.')
                    ->success()
                    ->sendToDatabase($verifikators);
            }
        });
    }

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

    public function verifikasis()
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

    public function pengesahans()
    {
        return $this->hasMany(Pengesahan::class, 'dokumen_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'dokumen_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'dokumen_id');
    }
}
