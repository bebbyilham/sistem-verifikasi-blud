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

class PembayaransRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayarans';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bendahara_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bendahara_id')
            ->columns([
                TextColumn::make('bendahara.name')
                    ->label('Bendahara'),
                TextColumn::make('nomor_spj')
                    ->label('Nomor SPJ'),
                TextColumn::make('status_bayar')
                    ->badge()
                    ->color('success'),
                TextColumn::make('tanggal_bayar')
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
