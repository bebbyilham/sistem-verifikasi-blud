<?php

namespace App\Filament\Resources\JenisDokumens\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JenisDokumenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_jenis')
                    ->label('Kode Jenis')
                    ->required()
                    ->maxLength(30),
                TextInput::make('nama_jenis')
                    ->label('Nama Jenis Dokumen')
                    ->required()
                    ->maxLength(100),
                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
            ]);
    }
}
