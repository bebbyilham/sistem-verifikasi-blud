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
            ->columns(2)
            ->components([
                TextInput::make('kode_dokumen')
                    ->disabled()
                    ->dehydrated(false)
                    ->hiddenOn('create')
                    ->columnSpanFull(),
                Select::make('bidang_id')
                    ->label('Bidang / Unit Kerja')
                    ->relationship('bidang', 'nama_bidang')
                    ->default(fn () => auth()->user()?->bidang_id)
                    ->disabled(fn () => auth()->user()?->hasRole('pptk') && filled(auth()->user()?->bidang_id))
                    ->dehydrated()
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
                    ->label('Nominal Pengajuan')
                    ->required()
                    ->prefix('Rp')
                    ->mask(RawJs::make('$money($input, ".", ",", 0)'))
                    ->stripCharacters('.')
                    ->formatStateUsing(fn ($state) => filled($state) ? number_format((float) $state, 0, ',', '.') : null)
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? (float) str_replace(['.', ','], ['', '.'], (string) $state) : null),
                DatePicker::make('tanggal_ajuan')
                    ->default(now())
                    ->required(),
                \Filament\Forms\Components\Hidden::make('status')
                    ->default('diajukan'),
                \Filament\Forms\Components\Repeater::make('file_path')
                    ->label('Dokumen Lampiran')
                    ->schema([
                        Select::make('tipe_sumber')
                            ->label('Sumber Dokumen')
                            ->options([
                                'upload' => '📄 Unggah File PDF (Maksimal 1MB)',
                                'link' => '🔗 Tautan Google Drive (Jika File > 1MB)',
                            ])
                            ->default('upload')
                            ->live()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('judul')
                            ->label('Judul / Nama Dokumen')
                            ->placeholder('Contoh: Kwitansi Pembelian, SPJ Obat, Bukti Transfer')
                            ->required(),
                        FileUpload::make('file')
                            ->label('File PDF (Maksimal 1MB)')
                            ->disk('public')
                            ->directory('dokumen_pengeluaran')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->openable()
                            ->downloadable()
                            ->helperText('Maksimal 1MB. Jika file > 1MB, pilih opsi Google Drive.')
                            ->visible(fn ($get) => $get('tipe_sumber') !== 'link')
                            ->required(fn ($get) => $get('tipe_sumber') !== 'link'),
                        TextInput::make('link_drive')
                            ->label('Tautan Google Drive')
                            ->placeholder('https://drive.google.com/file/d/...')
                            ->url()
                            ->helperText('Masukkan URL Google Drive (Akses file: Public / Anyone with link).')
                            ->visible(fn ($get) => $get('tipe_sumber') === 'link')
                            ->required(fn ($get) => $get('tipe_sumber') === 'link'),
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
