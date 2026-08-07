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

class VerifikasisRelationManager extends RelationManager
{
    protected static string $relationship = 'verifikasis';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('verifikator_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('verifikator_id')
            ->columns([
                Tables\Columns\TextColumn::make('verifikator.name')
                    ->label('Verifikator'),
                Tables\Columns\TextColumn::make('hasil')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lolos' => 'success',
                        'dikembalikan' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('catatan'),
                Tables\Columns\TextColumn::make('tanggal_verifikasi')
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
