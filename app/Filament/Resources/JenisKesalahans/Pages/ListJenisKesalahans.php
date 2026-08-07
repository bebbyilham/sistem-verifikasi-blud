<?php

namespace App\Filament\Resources\JenisKesalahans\Pages;

use App\Filament\Resources\JenisKesalahans\JenisKesalahanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJenisKesalahans extends ListRecords
{
    protected static string $resource = JenisKesalahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
