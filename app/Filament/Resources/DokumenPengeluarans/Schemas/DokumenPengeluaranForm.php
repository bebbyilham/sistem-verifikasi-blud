<?php

namespace App\Filament\Resources\DokumenPengeluarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DokumenPengeluaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_dokumen')
                    ->disabled()
                    ->dehydrated(false)
                    ->hiddenOn('create'),
                Select::make('bidang_id')
                    ->relationship('bidang', 'nama_bidang')
                    ->required(),
                \Filament\Forms\Components\Hidden::make('pptk_id')
                    ->default(fn () => auth()->id()),
                Select::make('jenis_dokumen')
                    ->options(fn () => \App\Models\JenisDokumen::pluck('nama_jenis', 'kode_jenis')->toArray())
                    ->searchable()
                    ->required(),
                Select::make('sumber_dana')
                    ->options(['BLUD' => 'BLUD', 'APBD' => 'APBD'])
                    ->required(),
                TextInput::make('nominal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                DatePicker::make('tanggal_ajuan')
                    ->default(now())
                    ->required(),
                \Filament\Forms\Components\Hidden::make('status')
                    ->default('diajukan'),
                \Filament\Forms\Components\FileUpload::make('file_path')
                    ->label('Dokumen Lampiran (PDF)')
                    ->disk('local')
                    ->directory('dokumen_pengeluaran')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
