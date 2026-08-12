<?php

namespace App\Observers;

use App\Models\AuditTrail;
use App\Models\DokumenPengeluaran;
use App\Models\Verifikasi;
use App\Models\Pengesahan;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Model;

class AuditTrailObserver
{
    public function created(Model $model): void
    {
        if ($model instanceof DokumenPengeluaran) {
            $this->recordAudit('Dokumen Diajukan - Status: ' . $model->status, 'dokumen_pengeluarans', $model->id);
        } elseif ($model instanceof Verifikasi) {
            $this->recordAudit('Verifikasi: ' . $model->hasil . ' - Dok #' . $model->dokumen_id, 'verifikasis', $model->id);
        } elseif ($model instanceof Pengesahan) {
            $this->recordAudit('Pengesahan - Dok #' . $model->dokumen_id, 'pengesahans', $model->id);
        } elseif ($model instanceof Pembayaran) {
            $this->recordAudit('Pembayaran SPJ: ' . ($model->nomor_spj ?? '-') . ' - Dok #' . $model->dokumen_id, 'pembayarans', $model->id);
        }
    }

    public function updated(Model $model): void
    {
        if ($model instanceof DokumenPengeluaran) {
            $changes = $model->getChanges();
            $aksi = isset($changes['status'])
                ? 'Status: ' . $model->getOriginal('status') . ' -> ' . $changes['status']
                : 'Data dokumen diperbarui';
            $this->recordAudit($aksi, 'dokumen_pengeluarans', $model->id);
        }
    }

    public function deleted(Model $model): void
    {
        if ($model instanceof DokumenPengeluaran) {
            $this->recordAudit('Dokumen Dihapus: ' . $model->kode_dokumen, 'dokumen_pengeluarans', $model->id);
        }
    }

    private function recordAudit(string $aksi, string $tabel, int $id): void
    {
        try {
            AuditTrail::create([
                'user_id' => auth()->id(),
                'aksi' => mb_substr($aksi, 0, 100),
                'tabel_terdampak' => $tabel,
                'id_data_terdampak' => $id,
                'waktu' => now(),
                'ip_address' => request()?->ip(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('AuditTrail gagal dicatat: ' . $e->getMessage());
        }
    }
}
