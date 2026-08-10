<?php

namespace App\Filament\Resources\DokumenPengeluarans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RiwayatKoreksisRelationManager extends RelationManager
{
    protected static string $relationship = 'riwayatKoreksis';

    protected static ?string $title = 'Riwayat Koreksi';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('versi_ke')
            ->columns([
                TextColumn::make('versi_ke')
                    ->label('Versi')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                TextColumn::make('jenisKesalahan.nama_kesalahan')
                    ->label('Jenis Kesalahan')
                    ->badge()
                    ->color('danger'),
                TextColumn::make('catatan_koreksi')
                    ->label('Catatan Koreksi')
                    ->limit(80)
                    ->wrap(),
                TextColumn::make('pengoreksi.name')
                    ->label('Dikoreksi Oleh'),
                TextColumn::make('tanggal_koreksi')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultSort('versi_ke', 'desc')
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
