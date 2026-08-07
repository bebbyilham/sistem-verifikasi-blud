<?php

namespace App\Filament\Resources\Bidangs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BidangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_bidang')
                    ->required(),
                TextInput::make('pptk_penanggung_jawab'),
            ]);
    }
}
