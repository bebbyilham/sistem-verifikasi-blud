<?php

namespace App\Filament\Resources\JenisKesalahans;

use App\Filament\Resources\JenisKesalahans\Pages\CreateJenisKesalahan;
use App\Filament\Resources\JenisKesalahans\Pages\EditJenisKesalahan;
use App\Filament\Resources\JenisKesalahans\Pages\ListJenisKesalahans;
use App\Filament\Resources\JenisKesalahans\Schemas\JenisKesalahanForm;
use App\Filament\Resources\JenisKesalahans\Tables\JenisKesalahansTable;
use App\Models\JenisKesalahan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JenisKesalahanResource extends Resource
{
    protected static ?string $model = JenisKesalahan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']);
    }

    public static function form(Schema $schema): Schema
    {
        return JenisKesalahanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisKesalahansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJenisKesalahans::route('/'),
        ];
    }
}
