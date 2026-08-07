<?php

namespace App\Filament\Resources\DokumenPengeluarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

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
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, ",", ".", 0)'))
                    ->stripCharacters('.')
                    ->formatStateUsing(fn ($state) => filled($state) ? number_format((float) str_replace('.', '', $state), 0, ',', '.') : null)
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? str_replace('.', '', $state) : null)
                    ->numeric(),
                DatePicker::make('tanggal_ajuan')
                    ->default(now())
                    ->required(),
                \Filament\Forms\Components\Hidden::make('status')
                    ->default('diajukan'),
                \Filament\Forms\Components\Repeater::make('file_path')
                    ->label('Dokumen Lampiran (PDF)')
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul / Nama Dokumen')
                            ->placeholder('Contoh: Kwitansi Pembelian, SPJ Obat, Bukti Transfer')
                            ->required(),
                        FileUpload::make('file')
                            ->label('File PDF')
                            ->disk('public')
                            ->directory('dokumen_pengeluaran')
                            ->acceptedFileTypes(['application/pdf'])
                            ->openable()
                            ->downloadable()
                            ->required(),
                    ])
                    ->columns(2)
                    ->addActionLabel('Tambah Dokumen Lampiran')
                    ->reorderable()
                    ->collapsible()
                    ->defaultItems(1)
                    ->columnSpanFull(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
