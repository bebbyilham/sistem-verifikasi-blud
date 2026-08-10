<?php

namespace App\Filament\Resources\Bidangs;

use App\Filament\Resources\Bidangs\Pages\CreateBidang;
use App\Filament\Resources\Bidangs\Pages\EditBidang;
use App\Filament\Resources\Bidangs\Pages\ListBidangs;
use App\Filament\Resources\Bidangs\Schemas\BidangForm;
use App\Filament\Resources\Bidangs\Tables\BidangsTable;
use App\Models\Bidang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BidangResource extends Resource
{
    protected static ?string $model = Bidang::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super_admin']);
    }

    public static function form(Schema $schema): Schema
    {
        return BidangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BidangsTable::configure($table);
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
            'index' => ListBidangs::route('/'),
        ];
    }
}
