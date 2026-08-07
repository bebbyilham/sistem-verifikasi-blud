<?php

namespace App\Filament\Resources\DokumenPengeluarans;

use App\Filament\Resources\DokumenPengeluarans\Pages\CreateDokumenPengeluaran;
use App\Filament\Resources\DokumenPengeluarans\Pages\EditDokumenPengeluaran;
use App\Filament\Resources\DokumenPengeluarans\Pages\ListDokumenPengeluarans;
use App\Filament\Resources\DokumenPengeluarans\Schemas\DokumenPengeluaranForm;
use App\Filament\Resources\DokumenPengeluarans\Tables\DokumenPengeluaransTable;
use App\Models\DokumenPengeluaran;
use BackedEnum;
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
