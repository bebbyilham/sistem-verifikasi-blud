<?php

namespace App\Filament\Resources\DokumenPengeluarans;

use App\Filament\Resources\DokumenPengeluarans\Pages\CreateDokumenPengeluaran;
use App\Filament\Resources\DokumenPengeluarans\Pages\EditDokumenPengeluaran;
use App\Filament\Resources\DokumenPengeluarans\Pages\ListDokumenPengeluarans;
use App\Filament\Resources\DokumenPengeluarans\Schemas\DokumenPengeluaranForm;
use App\Filament\Resources\DokumenPengeluarans\Tables\DokumenPengeluaransTable;
use App\Models\DokumenPengeluaran;
use BackedEnum;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DokumenPengeluaranResource extends Resource
{
    protected static ?string $model = DokumenPengeluaran::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('pptk');
    }

    public static function form(Schema $schema): Schema
    {
        return DokumenPengeluaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DokumenPengeluaransTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Detail Dokumen Pengeluaran')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(3)
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('kode_dokumen')
                                    ->label('Kode Dokumen'),
                                \Filament\Forms\Components\TextInput::make('status')
                                    ->label('Status'),
                                \Filament\Forms\Components\TextInput::make('tanggal_ajuan')
                                    ->label('Tanggal Pengajuan'),
                                \Filament\Forms\Components\TextInput::make('bidang.nama_bidang')
                                    ->label('Bidang / Unit Kerja'),
                                \Filament\Forms\Components\TextInput::make('jenis_dokumen')
                                    ->label('Jenis Dokumen'),
                                \Filament\Forms\Components\TextInput::make('sumber_dana')
                                    ->label('Sumber Dana'),
                                \Filament\Forms\Components\TextInput::make('nominal')
                                    ->label('Nominal Pengajuan')
                                    ->prefix('Rp')
                                    ->formatStateUsing(fn ($state) => filled($state) ? number_format((float) $state, 0, ',', '.') : null),
                                \Filament\Forms\Components\Textarea::make('keterangan')
                                    ->label('Keterangan')
                                    ->columnSpanFull(),
                            ]),
                    ]),
                \Filament\Schemas\Components\Section::make('Dokumen Lampiran (PDF)')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('file_path_display')
                            ->label('')
                            ->content(function ($record) {
                                $files = $record?->file_path;
                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?? [];
                                }
                                $files = array_filter((array) $files);

                                if (empty($files)) {
                                    return new \Illuminate\Support\HtmlString('<div style="padding: 16px; text-align: center; font-size: 13px; color: #6b7280; font-style: italic; background-color: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">Tidak ada file lampiran.</div>');
                                }

                                $rows = [];
                                foreach (array_values($files) as $index => $item) {
                                    if (is_array($item)) {
                                        $filePath = $item['file'] ?? null;
                                        $judul = !empty($item['judul']) ? $item['judul'] : ('Dokumen ' . ($index + 1));
                                    } else {
                                        $filePath = (string) $item;
                                        $judul = 'Dokumen ' . ($index + 1);
                                    }

                                    if (empty($filePath)) {
                                        continue;
                                    }

                                    $url = asset('storage/' . $filePath);

                                    $rows[] = '<tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 12px 16px; text-align: center; font-size: 13px; color: #4b5563; width: 60px;">' . ($index + 1) . '</td>
                                        <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: #111827;">' . e($judul) . '</td>
                                        <td style="padding: 12px 16px; text-align: center; width: 100px;">
                                            <span style="display: inline-block; padding: 2px 8px; font-size: 10px; font-weight: 700; color: #b91c1c; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 4px;">PDF</span>
                                        </td>
                                        <td style="padding: 12px 16px; text-align: center; width: 140px; white-space: nowrap;">
                                            <a href="' . e($url) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: #2563eb; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.backgroundColor=\'#dbeafe\'" onmouseout="this.style.backgroundColor=\'#eff6ff\'">' .
                                                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; vertical-align: middle;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>' .
                                                '<span style="margin-left: 3px;">Buka PDF</span>' .
                                            '</a>' .
                                        '</td>' .
                                    '</tr>';
                                }

                                return new \Illuminate\Support\HtmlString('<div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); max-width: 100%;">' .
                                    '<table style="width: 100%; border-collapse: collapse; text-align: left;">' .
                                        '<thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">' .
                                            '<tr>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 60px; text-align: center;">No</th>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em;">Judul / Nama Dokumen</th>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 100px; text-align: center;">Format</th>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 140px; text-align: center;">Aksi</th>' .
                                            '</tr>' .
                                        '</thead>' .
                                        '<tbody>' . implode('', $rows) . '</tbody>' .
                                    '</table>' .
                                '</div>');
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\DokumenPengeluarans\RelationManagers\VerifikasisRelationManager::class,
            \App\Filament\Resources\DokumenPengeluarans\RelationManagers\PengesahansRelationManager::class,
            \App\Filament\Resources\DokumenPengeluarans\RelationManagers\PembayaransRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDokumenPengeluarans::route('/'),
            'view' => \App\Filament\Resources\DokumenPengeluarans\Pages\ViewDokumenPengeluaran::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('pptk')) {
            $query->where('pptk_id', $user->id);
        } elseif ($user->hasRole('verifikator')) {
            $query->whereIn('status', ['diajukan', 'revisi']);
        } elseif ($user->hasRole('ppk')) {
            $query->where('status', 'verifikasi');
        } elseif ($user->hasRole('bendahara')) {
            $query->where('status', 'sah');
        }

        return $query;
    }
}
