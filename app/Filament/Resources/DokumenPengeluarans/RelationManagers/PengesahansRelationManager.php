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
                Tables\Columns\TextColumn::make('ppk.name')
                    ->label('PPK'),
                Tables\Columns\TextColumn::make('catatan'),
                Tables\Columns\TextColumn::make('tanggal_sah')
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
