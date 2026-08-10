<?php

namespace App\Observers;

use App\Models\AuditTrail;
use App\Models\DokumenPengeluaran;

class AuditTrailObserver
{
    public function created(DokumenPengeluaran $dokumen): void
    {
        $this->recordAudit($dokumen, 'Dokumen Diajukan — Status: ' . $dokumen->status);
    }

    public function updated(DokumenPengeluaran $dokumen): void
    {
        $changes = $dokumen->getChanges();

        if (isset($changes['status'])) {
            $aksi = 'Update Status Dokumen: ' . $dokumen->getOriginal('status') . ' → ' . $changes['status'];
        } else {
            $aksi = 'Update Dokumen (data diperbarui)';
        }

        $this->recordAudit($dokumen, $aksi);
    }

    public function deleted(DokumenPengeluaran $dokumen): void
    {
        $this->recordAudit($dokumen, 'Dokumen Dihapus — Kode: ' . $dokumen->kode_dokumen);
    }

    private function recordAudit(DokumenPengeluaran $dokumen, string $aksi): void
    {
        AuditTrail::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'tabel_terdampak' => 'dokumen_pengeluarans',
            'id_data_terdampak' => $dokumen->id,
            'waktu' => now(),
            'ip_address' => request()?->ip(),
        ]);
    }
}
