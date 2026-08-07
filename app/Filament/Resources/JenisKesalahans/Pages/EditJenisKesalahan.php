<?php

namespace App\Filament\Resources\JenisKesalahans\Pages;

use App\Filament\Resources\JenisKesalahans\JenisKesalahanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJenisKesalahan extends EditRecord
{
    protected static string $resource = JenisKesalahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
