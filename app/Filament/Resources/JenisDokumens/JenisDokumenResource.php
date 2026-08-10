<?php

namespace App\Filament\Resources\JenisDokumens;

use App\Filament\Resources\JenisDokumens\Pages\ListJenisDokumens;
use App\Filament\Resources\JenisDokumens\Schemas\JenisDokumenForm;
use App\Filament\Resources\JenisDokumens\Tables\JenisDokumensTable;
use App\Models\JenisDokumen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class JenisDokumenResource extends Resource
{
    protected static ?string $model = JenisDokumen::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Jenis Dokumen';

    protected static ?string $modelLabel = 'Jenis Dokumen';

    protected static ?string $pluralModelLabel = 'Jenis Dokumen';

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']);
    }

    public static function form(Schema $schema): Schema
    {
        return JenisDokumenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisDokumensTable::configure($table);
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
            'index' => ListJenisDokumens::route('/'),
        ];
    }
}
