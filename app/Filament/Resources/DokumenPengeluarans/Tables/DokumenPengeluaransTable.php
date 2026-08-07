<?php

namespace App\Filament\Resources\DokumenPengeluarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DokumenPengeluaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_dokumen')
                    ->searchable(),
                TextColumn::make('bidang.nama_bidang')
                    ->sortable(),
                TextColumn::make('jenis_dokumen')
                    ->searchable(),
                TextColumn::make('sumber_dana')
                    ->badge(),
                TextColumn::make('nominal')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('tanggal_ajuan')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diajukan' => 'warning',
                        'verifikasi' => 'primary',
                        'revisi' => 'danger',
                        'sah' => 'success',
                        'dibayar' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('file_path')
                    ->label('Lampiran')
                    ->formatStateUsing(fn ($state) => 'Lihat File')
                    ->url(fn ($record) => url('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make()->iconButton(),
                EditAction::make()
                    ->iconButton()
                    ->visible(fn (\App\Models\DokumenPengeluaran $record) => auth()->user()->hasRole('pptk') && in_array($record->status, ['diajukan', 'revisi'])),
                \Filament\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (\App\Models\DokumenPengeluaran $record) => auth()->user()->hasRole('verifikator') && in_array($record->status, ['diajukan', 'revisi']))
                    ->form([
                        \Filament\Forms\Components\Select::make('hasil')
                            ->options([
                                'lolos' => 'Lolos Verifikasi',
                                'dikembalikan' => 'Kembalikan (Revisi)',
                            ])
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->required(),
                    ])
                    ->action(function (\App\Models\DokumenPengeluaran $record, array $data) {
                        \App\Models\Verifikasi::create([
                            'dokumen_id' => $record->id,
                            'verifikator_id' => auth()->id(),
                            'hasil' => $data['hasil'],
                            'tanggal_verifikasi' => now(),
                            'catatan' => $data['catatan'],
                        ]);

                        $newStatus = $data['hasil'] === 'lolos' ? 'verifikasi' : 'revisi';
                        $record->update(['status' => $newStatus]);

                        \Filament\Notifications\Notification::make()
                            ->title('Status Dokumen Diperbarui')
                            ->body('Dokumen Anda telah ' . ($data['hasil'] === 'lolos' ? 'diverifikasi' : 'dikembalikan untuk direvisi') . '.')
                            ->success()
                            ->sendToDatabase($record->pptk);
                    }),
                \Filament\Actions\Action::make('sah')
                    ->label('Sahkan Dokumen')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (\App\Models\DokumenPengeluaran $record) => auth()->user()->hasRole('ppk') && $record->status === 'verifikasi')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('catatan'),
                    ])
                    ->action(function (\App\Models\DokumenPengeluaran $record, array $data) {
                        \App\Models\Pengesahan::create([
                            'dokumen_id' => $record->id,
                            'ppk_id' => auth()->id(),
                            'tanggal_sah' => now(),
                            'catatan' => $data['catatan'] ?? null,
                        ]);

                        $record->update(['status' => 'sah']);

                        \Filament\Notifications\Notification::make()
                            ->title('Dokumen Telah Disahkan')
                            ->body('Dokumen ' . $record->kode_dokumen . ' telah disahkan oleh PPK.')
                            ->success()
                            ->sendToDatabase($record->pptk);
                    })
                    ->requiresConfirmation(),
                \Filament\Actions\Action::make('bayar')
                    ->label('Bayar / Input SPJ')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->visible(fn (\App\Models\DokumenPengeluaran $record) => auth()->user()->hasRole('bendahara') && $record->status === 'sah')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('nomor_spj')
                            ->label('Nomor SPJ')
                            ->required(),
                    ])
                    ->action(function (\App\Models\DokumenPengeluaran $record, array $data) {
                        \App\Models\Pembayaran::create([
                            'dokumen_id' => $record->id,
                            'bendahara_id' => auth()->id(),
                            'tanggal_bayar' => now(),
                            'nomor_spj' => $data['nomor_spj'],
                            'status_bayar' => 'Lunas',
                        ]);

                        $record->update(['status' => 'dibayar']);

                        \Filament\Notifications\Notification::make()
                            ->title('Dana Telah Dicairkan')
                            ->body('Dana untuk Dokumen ' . $record->kode_dokumen . ' telah dicairkan.')
                            ->success()
                            ->sendToDatabase($record->pptk);
                    }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
