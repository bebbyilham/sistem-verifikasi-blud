<?php

namespace App\Filament\Resources\DokumenPengeluarans\Pages;

use App\Filament\Resources\DokumenPengeluarans\DokumenPengeluaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDokumenPengeluaran extends CreateRecord
{
    protected static string $resource = DokumenPengeluaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lastDoc = \App\Models\DokumenPengeluaran::latest('id')->first();
        $nextId = $lastDoc ? $lastDoc->id + 1 : 1;
        
        $data['kode_dokumen'] = 'BLUD-' . date('Y-m') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $document = $this->record;

        $verifikators = \App\Models\User::role('verifikator')->get();

        \Filament\Notifications\Notification::make()
            ->title('Dokumen Baru Diajukan')
            ->body('Dokumen ' . $document->kode_dokumen . ' telah diajukan oleh PPTK dan menunggu verifikasi.')
            ->success()
            ->sendToDatabase($verifikators);
    }
}
