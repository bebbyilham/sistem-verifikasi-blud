<?php

namespace App\Filament\Resources\DokumenPengeluarans\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PengesahansRelationManager extends RelationManager
{
    protected static string $relationship = 'pengesahans';

    protected static ?string $title = 'Pengesahan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ppk_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ppk_id')
            ->columns([
                TextColumn::make('ppk.name')
                    ->label('PPK'),
                TextColumn::make('catatan'),
                TextColumn::make('tanggal_sah')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
