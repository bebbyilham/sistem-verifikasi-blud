<?php

namespace App\Filament\Resources\DokumenPengeluarans\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('tanggal_ajuan')
                    ->date('d M Y')
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
                TextColumn::make('file_path_display')
                    ->label('Lampiran')
                    ->state(function (\App\Models\DokumenPengeluaran $record) {
                        $files = $record->file_path;
                        if (is_string($files)) {
                            $files = json_decode($files, true) ?? [];
                        }
                        $files = array_filter((array) $files);
                        $total = count($files);

                        if ($total === 0) {
                            return '-';
                        }

                        $label = $total . ' File';

                        return new HtmlString('<span style="display: inline-flex; align-items: center; justify-content: center; gap: 5px; padding: 4px 10px; border-radius: 6px; background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: all 0.15s ease;" onmouseover="this.style.backgroundColor=\'#dbeafe\'" onmouseout="this.style.backgroundColor=\'#eff6ff\'">' .
                            '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; flex-shrink: 0; vertical-align: middle;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>' .
                            '<span style="line-height: 1; vertical-align: middle; white-space: nowrap;">' . e($label) . '</span>' .
                            '</span>');
                    })
                    ->action(
                        Action::make('lihat_lampiran_modal')
                            ->modalHeading(fn (\App\Models\DokumenPengeluaran $record) => 'Daftar Dokumen Lampiran - ' . $record->kode_dokumen)
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                            ->modalContent(function (\App\Models\DokumenPengeluaran $record) {
                                $files = $record->file_path;
                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?? [];
                                }
                                $files = array_filter((array) $files);

                                if (empty($files)) {
                                    return new HtmlString('<div style="padding: 16px; text-align: center; font-size: 13px; color: #6b7280; font-style: italic; background-color: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">Tidak ada file lampiran untuk dokumen ini.</div>');
                                }

                                $rows = [];
                                foreach (array_values($files) as $index => $item) {
                                    if (is_array($item)) {
                                        $filePath = $item['file'] ?? null;
                                        $judul = !empty($item['judul']) ? $item['judul'] : ('Dokumen ' . ($index + 1));
                                    } else {
                                        $filePath = (string) $item;
                                        $judul = 'Dokumen ' . ($index + 1);
                                    }

                                    if (empty($filePath)) {
                                        continue;
                                    }

                                    $url = asset('storage/' . $filePath);

                                    $rows[] = '<tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 12px 16px; text-align: center; font-size: 13px; color: #4b5563;">' . ($index + 1) . '</td>
                                        <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: #111827;">' . e($judul) . '</td>
                                        <td style="padding: 12px 16px; text-align: center;">
                                            <span style="display: inline-block; padding: 2px 8px; font-size: 10px; font-weight: 700; color: #b91c1c; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 4px;">PDF</span>
                                        </td>
                                        <td style="padding: 12px 16px; text-align: center; white-space: nowrap;">
                                            <a href="' . e($url) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: #2563eb; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.backgroundColor=\'#dbeafe\'" onmouseout="this.style.backgroundColor=\'#eff6ff\'">' .
                                                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; vertical-align: middle;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>' .
                                                '<span style="margin-left: 3px;">Buka PDF</span>' .
                                            '</a>' .
                                        '</td>' .
                                    '</tr>';
                                }

                                return new HtmlString('<div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">' .
                                    '<table style="width: 100%; border-collapse: collapse; text-align: left;">' .
                                        '<thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">' .
                                            '<tr>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 60px; text-align: center;">No</th>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em;">Judul / Nama Dokumen</th>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 100px; text-align: center;">Format</th>' .
                                                '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 140px; text-align: center;">Aksi</th>' .
                                            '</tr>' .
                                        '</thead>' .
                                        '<tbody>' . implode('', $rows) . '</tbody>' .
                                    '</table>' .
                                '</div>');
                            })
                    )
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('lihat_lampiran_row')
                    ->label('Lampiran')
                    ->icon('heroicon-o-paper-clip')
                    ->iconButton()
                    ->color('info')
                    ->tooltip('Lihat Daftar Lampiran')
                    ->modalHeading(fn (\App\Models\DokumenPengeluaran $record) => 'Daftar Dokumen Lampiran - ' . $record->kode_dokumen)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function (\App\Models\DokumenPengeluaran $record) {
                        $files = $record->file_path;
                        if (is_string($files)) {
                            $files = json_decode($files, true) ?? [];
                        }
                        $files = array_filter((array) $files);

                        if (empty($files)) {
                            return new HtmlString('<div style="padding: 16px; text-align: center; font-size: 13px; color: #6b7280; font-style: italic; background-color: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">Tidak ada file lampiran untuk dokumen ini.</div>');
                        }

                        $rows = [];
                        foreach (array_values($files) as $index => $item) {
                            if (is_array($item)) {
                                $filePath = $item['file'] ?? null;
                                $judul = !empty($item['judul']) ? $item['judul'] : ('Dokumen ' . ($index + 1));
                            } else {
                                $filePath = (string) $item;
                                $judul = 'Dokumen ' . ($index + 1);
                            }

                            if (empty($filePath)) {
                                continue;
                            }

                            $url = asset('storage/' . $filePath);

                            $rows[] = '<tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px 16px; text-align: center; font-size: 13px; color: #4b5563;">' . ($index + 1) . '</td>
                                <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: #111827;">' . e($judul) . '</td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <span style="display: inline-block; padding: 2px 8px; font-size: 10px; font-weight: 700; color: #b91c1c; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 4px;">PDF</span>
                                </td>' .
                                '<td style="padding: 12px 16px; text-align: center; white-space: nowrap;">
                                    <a href="' . e($url) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: #2563eb; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.backgroundColor=\'#dbeafe\'" onmouseout="this.style.backgroundColor=\'#eff6ff\'">' .
                                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; vertical-align: middle;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>' .
                                        '<span style="margin-left: 3px;">Buka PDF</span>' .
                                    '</a>' .
                                '</td>' .
                            '</tr>';
                        }

                        return new HtmlString('<div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">' .
                            '<table style="width: 100%; border-collapse: collapse; text-align: left;">' .
                                '<thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">' .
                                    '<tr>' .
                                        '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 60px; text-align: center;">No</th>' .
                                        '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em;">Judul / Nama Dokumen</th>' .
                                        '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 100px; text-align: center;">Format</th>' .
                                        '<th style="padding: 10px 16px; font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; width: 140px; text-align: center;">Aksi</th>' .
                                    '</tr>' .
                                '</thead>' .
                                '<tbody>' . implode('', $rows) . '</tbody>' .
                            '</table>' .
                        '</div>');
                    }),
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
