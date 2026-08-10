<?php

namespace App\Filament\Resources\AuditTrails;

use App\Filament\Resources\AuditTrails\Pages\ListAuditTrails;
use App\Models\AuditTrail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditTrailResource extends Resource
{
    protected static ?string $model = AuditTrail::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-finger-print';

    protected static \UnitEnum|string|null $navigationGroup = 'Monitoring';

    protected static ?string $navigationLabel = 'Audit Trail';

    protected static ?int $navigationSort = 99;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin', 'manajemen']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('waktu')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('aksi')
                    ->label('Aksi')
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                TextColumn::make('tabel_terdampak')
                    ->label('Tabel')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('id_data_terdampak')
                    ->label('ID Data'),
                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('waktu', 'desc')
            ->filters([
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name'),
                SelectFilter::make('tabel_terdampak')
                    ->label('Tabel')
                    ->options([
                        'dokumen_pengeluarans' => 'Dokumen Pengeluaran',
                        'verifikasis' => 'Verifikasi',
                        'pengesahans' => 'Pengesahan',
                        'pembayarans' => 'Pembayaran',
                    ]),
            ])
            ->recordActions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditTrails::route('/'),
        ];
    }
}
