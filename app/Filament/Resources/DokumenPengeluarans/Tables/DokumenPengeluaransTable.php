<?php

namespace App\Filament\Resources\DokumenPengeluarans\Tables;

use App\Models\DokumenPengeluaran;
use App\Models\JenisKesalahan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                        DokumenPengeluaran::STATUS_DIAJUKAN => 'warning',
                        DokumenPengeluaran::STATUS_DIVERIFIKASI => 'info',
                        DokumenPengeluaran::STATUS_DIKEMBALIKAN => 'danger',
                        DokumenPengeluaran::STATUS_DISAHKAN => 'success',
                        DokumenPengeluaran::STATUS_DIBAYAR => 'success',
                        DokumenPengeluaran::STATUS_DIARSIPKAN => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('file_path_display')
                    ->label('Lampiran')
                    ->state(function (DokumenPengeluaran $record) {
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

                        return new HtmlString('<span style="display: inline-flex; align-items: center; justify-content: center; gap: 5px; padding: 4px 10px; border-radius: 6px; background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 600; cursor: pointer; white-space: nowrap;">' .
                            '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; flex-shrink: 0; vertical-align: middle;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>' .
                            '<span style="line-height: 1; vertical-align: middle; white-space: nowrap;">' . e($label) . '</span>' .
                            '</span>');
                    })
                    ->action(
                        Action::make('lihat_lampiran_modal')
                            ->modalHeading(fn (DokumenPengeluaran $record) => 'Daftar Dokumen Lampiran - ' . $record->kode_dokumen)
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                            ->modalContent(fn (DokumenPengeluaran $record) => static::renderLampiranModal($record))
                    )
                    ->html(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(array_combine(DokumenPengeluaran::ALL_STATUSES, array_map('ucfirst', DokumenPengeluaran::ALL_STATUSES))),
                SelectFilter::make('bidang_id')
                    ->label('Bidang')
                    ->relationship('bidang', 'nama_bidang'),
                SelectFilter::make('sumber_dana')
                    ->options(['BLUD' => 'BLUD', 'APBD' => 'APBD']),
            ])
            ->recordActions([
                Action::make('lihat_lampiran_row')
                    ->label('Lampiran')
                    ->icon('heroicon-o-paper-clip')
                    ->iconButton()
                    ->color('info')
                    ->tooltip('Lihat Daftar Lampiran')
                    ->modalHeading(fn (DokumenPengeluaran $record) => 'Daftar Dokumen Lampiran - ' . $record->kode_dokumen)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (DokumenPengeluaran $record) => static::renderLampiranModal($record)),
                \Filament\Actions\ViewAction::make()->iconButton(),
                EditAction::make()
                    ->iconButton()
                    ->visible(fn (DokumenPengeluaran $record) => auth()->user()->hasRole('pptk') && in_array($record->status, [DokumenPengeluaran::STATUS_DIAJUKAN, DokumenPengeluaran::STATUS_DIKEMBALIKAN])),

                // === AKSI PPTK (Kirim Ulang Dokumen Dikembalikan) ===
                Action::make('kirim_ulang')
                    ->label('Ajukan Ulang')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->iconButton()
                    ->tooltip('Ajukan Ulang Dokumen ke Verifikator')
                    ->modalHeading('Ajukan Ulang Dokumen ke Verifikator')
                    ->modalDescription('Dokumen yang telah Anda lengkapi / perbaiki akan dikirimkan kembali ke Verifikator untuk diperiksa ulang.')
                    ->modalSubmitActionLabel('Kirim Ulang ke Verifikator')
                    ->visible(fn (DokumenPengeluaran $record) => auth()->user()->hasRole('pptk') && $record->status === DokumenPengeluaran::STATUS_DIKEMBALIKAN)
                    ->form([
                        \Filament\Forms\Components\Textarea::make('catatan_perbaikan')
                            ->label('Catatan Perbaikan / Kelengkapan (Opsional)')
                            ->placeholder('Jelaskan bagian data atau lampiran yang telah Anda perbaiki...'),
                    ])
                    ->action(function (DokumenPengeluaran $record, array $data) {
                        $record->update(['status' => DokumenPengeluaran::STATUS_DIAJUKAN]);

                        // Notifikasi ke Verifikator
                        $verifikators = \App\Models\User::role('verifikator')->get();
                        if ($verifikators->isNotEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Dokumen Diajukan Ulang')
                                ->body('Dokumen ' . $record->kode_dokumen . ' telah diperbaiki oleh PPTK (' . auth()->user()->name . ') dan diajukan ulang untuk verifikasi.')
                                ->info()
                                ->sendToDatabase($verifikators);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Dokumen Berhasil Diajukan Ulang')
                            ->body('Dokumen ' . $record->kode_dokumen . ' telah dikirimkan ke Verifikator untuk diperiksa kembali.')
                            ->success()
                            ->send();
                    }),

                // === AKSI VERIFIKASI (Verifikator) ===
                Action::make('verifikasi_lolos')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->iconButton()
                    ->tooltip('Setujui & Verifikasi Dokumen')
                    ->modalHeading('Konfirmasi Verifikasi Dokumen')
                    ->modalDescription('Dokumen yang lolos verifikasi akan diteruskan ke PPK untuk pengesahan.')
                    ->modalSubmitActionLabel('Setujui & Verifikasi')
                    ->visible(fn (DokumenPengeluaran $record) => auth()->user()->hasRole('verifikator') && in_array($record->status, [DokumenPengeluaran::STATUS_DIAJUKAN, DokumenPengeluaran::STATUS_DIKEMBALIKAN]))
                    ->form([
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Verifikasi (Opsional)')
                            ->placeholder('Masukkan catatan verifikasi jika ada...'),
                    ])
                    ->action(function (DokumenPengeluaran $record, array $data) {
                        // Simpan record verifikasi
                        \App\Models\Verifikasi::create([
                            'dokumen_id' => $record->id,
                            'verifikator_id' => auth()->id(),
                            'hasil' => 'lolos',
                            'tanggal_verifikasi' => now(),
                            'catatan' => $data['catatan'] ?? 'Lolos Verifikasi',
                        ]);

                        $record->update(['status' => DokumenPengeluaran::STATUS_DIVERIFIKASI]);

                        \Filament\Notifications\Notification::make()
                            ->title('Dokumen Diverifikasi')
                            ->body('Dokumen ' . $record->kode_dokumen . ' telah lolos verifikasi dan menunggu pengesahan PPK.')
                            ->success()
                            ->sendToDatabase($record->pptk);

                        // Notifikasi ke PPK
                        $ppkUsers = \App\Models\User::role('ppk')->get();
                        if ($ppkUsers->isNotEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Dokumen Menunggu Pengesahan')
                                ->body('Dokumen ' . $record->kode_dokumen . ' telah diverifikasi dan menunggu pengesahan Anda.')
                                ->info()
                                ->sendToDatabase($ppkUsers);
                        }
                    }),

                Action::make('verifikasi_kembalikan')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->iconButton()
                    ->tooltip('Kembalikan Dokumen ke PPTK (Revisi)')
                    ->modalHeading('Kembalikan Dokumen ke PPTK (Revisi)')
                    ->modalDescription('Dokumen akan dikembalikan ke PPTK beserta catatan jenis kesalahan untuk diperbaiki.')
                    ->modalSubmitActionLabel('Kembalikan Dokumen')
                    ->visible(fn (DokumenPengeluaran $record) => auth()->user()->hasRole('verifikator') && in_array($record->status, [DokumenPengeluaran::STATUS_DIAJUKAN, DokumenPengeluaran::STATUS_DIKEMBALIKAN]))
                    ->form([
                        \Filament\Forms\Components\Select::make('jenis_kesalahan_id')
                            ->label('Kategori Jenis Kesalahan')
                            ->options(JenisKesalahan::pluck('nama_kesalahan', 'id'))
                            ->searchable()
                            ->required(),
                        \Filament\Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Revisi / Alasan Pengembalian')
                            ->placeholder('Jelaskan detail bagian dokumen yang perlu diperbaiki oleh PPTK...')
                            ->required(),
                    ])
                    ->action(function (DokumenPengeluaran $record, array $data) {
                        // Simpan record verifikasi
                        \App\Models\Verifikasi::create([
                            'dokumen_id' => $record->id,
                            'verifikator_id' => auth()->id(),
                            'hasil' => 'dikembalikan',
                            'tanggal_verifikasi' => now(),
                            'catatan' => $data['catatan'],
                        ]);

                        // Buat riwayat koreksi otomatis
                        \App\Models\RiwayatKoreksi::create([
                            'dokumen_id' => $record->id,
                            'versi_ke' => $record->riwayatKoreksis()->count() + 1,
                            'jenis_kesalahan_id' => $data['jenis_kesalahan_id'] ?? null,
                            'catatan_koreksi' => $data['catatan'],
                            'tanggal_koreksi' => now(),
                            'dikoreksi_oleh' => auth()->id(),
                        ]);

                        $record->update(['status' => DokumenPengeluaran::STATUS_DIKEMBALIKAN]);

                        \Filament\Notifications\Notification::make()
                            ->title('Dokumen Dikembalikan')
                            ->body('Dokumen ' . $record->kode_dokumen . ' dikembalikan untuk direvisi. Alasan: ' . ($data['catatan'] ?? '-'))
                            ->danger()
                            ->sendToDatabase($record->pptk);
                    }),

                // === AKSI PENGESAHAN (PPK) ===
                Action::make('sahkan')
                    ->label('Sahkan Dokumen')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (DokumenPengeluaran $record) => auth()->user()->hasRole('ppk') && $record->status === DokumenPengeluaran::STATUS_DIVERIFIKASI)
                    ->form([
                        \Filament\Forms\Components\Textarea::make('catatan'),
                    ])
                    ->action(function (DokumenPengeluaran $record, array $data) {
                        \App\Models\Pengesahan::create([
                            'dokumen_id' => $record->id,
                            'ppk_id' => auth()->id(),
                            'tanggal_sah' => now(),
                            'catatan' => $data['catatan'] ?? null,
                        ]);

                        $record->update(['status' => DokumenPengeluaran::STATUS_DISAHKAN]);

                        \Filament\Notifications\Notification::make()
                            ->title('Dokumen Telah Disahkan')
                            ->body('Dokumen ' . $record->kode_dokumen . ' telah disahkan oleh PPK.')
                            ->success()
                            ->sendToDatabase($record->pptk);

                        // Notifikasi ke Bendahara
                        $bendaharaUsers = \App\Models\User::role('bendahara')->get();
                        if ($bendaharaUsers->isNotEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Dokumen Menunggu Pembayaran')
                                ->body('Dokumen ' . $record->kode_dokumen . ' telah disahkan dan menunggu proses pembayaran.')
                                ->info()
                                ->sendToDatabase($bendaharaUsers);
                        }
                    })
                    ->requiresConfirmation(),

                // === AKSI PEMBAYARAN (Bendahara) ===
                Action::make('bayar')
                    ->label('Bayar / Input SPJ')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->visible(fn (DokumenPengeluaran $record) => auth()->user()->hasRole('bendahara') && $record->status === DokumenPengeluaran::STATUS_DISAHKAN)
                    ->form([
                        \Filament\Forms\Components\TextInput::make('nomor_spj')
                            ->label('Nomor SPJ')
                            ->required(),
                    ])
                    ->action(function (DokumenPengeluaran $record, array $data) {
                        \App\Models\Pembayaran::create([
                            'dokumen_id' => $record->id,
                            'bendahara_id' => auth()->id(),
                            'tanggal_bayar' => now(),
                            'nomor_spj' => $data['nomor_spj'],
                            'status_bayar' => 'Lunas',
                        ]);

                        $record->update(['status' => DokumenPengeluaran::STATUS_DIBAYAR]);

                        \Filament\Notifications\Notification::make()
                            ->title('Dana Telah Dicairkan')
                            ->body('Dana untuk Dokumen ' . $record->kode_dokumen . ' telah dicairkan.')
                            ->success()
                            ->sendToDatabase($record->pptk);
                    }),

                // === AKSI ARSIPKAN (Bendahara — manual) ===
                Action::make('arsipkan')
                    ->label('Arsipkan')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (DokumenPengeluaran $record) => auth()->user()->hasRole('bendahara') && $record->status === DokumenPengeluaran::STATUS_DIBAYAR)
                    ->requiresConfirmation()
                    ->modalHeading('Arsipkan Dokumen')
                    ->modalDescription('Dokumen yang diarsipkan menandakan seluruh proses telah selesai. Lanjutkan?')
                    ->action(function (DokumenPengeluaran $record) {
                        $record->update(['status' => DokumenPengeluaran::STATUS_DIARSIPKAN]);

                        \Filament\Notifications\Notification::make()
                            ->title('Dokumen Diarsipkan')
                            ->body('Dokumen ' . $record->kode_dokumen . ' telah diarsipkan.')
                            ->success()
                            ->sendToDatabase($record->pptk);
                    }),
            ])
            ->toolbarActions([
                //
            ]);
    }

    /**
     * Helper: render lampiran modal content (menghindari duplikasi HTML).
     */
    private static function renderLampiranModal(DokumenPengeluaran $record): HtmlString
    {
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
            $tipeSumber = is_array($item) ? ($item['tipe_sumber'] ?? null) : null;
            $linkDrive = is_array($item) ? ($item['link_drive'] ?? $item['link'] ?? null) : null;
            $filePath = is_array($item) ? ($item['file'] ?? null) : (string) $item;
            $judul = (is_array($item) && !empty($item['judul'])) ? $item['judul'] : ('Dokumen ' . ($index + 1));

            $isDriveLink = ($tipeSumber === 'link') || filled($linkDrive) || str_starts_with((string)$filePath, 'http://') || str_starts_with((string)$filePath, 'https://');

            if ($isDriveLink) {
                $url = $linkDrive ?: $filePath;
                if (empty($url)) {
                    continue;
                }
                $formatBadge = '<span style="display: inline-block; padding: 2px 8px; font-size: 10px; font-weight: 700; color: #d97706; background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 4px;">G-DRIVE</span>';
                $btnLabel = 'Buka G-Drive';
                $btnStyle = 'color: #d97706; background-color: #fffbeb; border: 1px solid #fde68a;';
                $iconSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; vertical-align: middle;"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>';
            } else {
                if (empty($filePath)) {
                    continue;
                }
                $url = asset('storage/' . $filePath);
                $formatBadge = '<span style="display: inline-block; padding: 2px 8px; font-size: 10px; font-weight: 700; color: #b91c1c; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 4px;">PDF</span>';
                $btnLabel = 'Buka PDF';
                $btnStyle = 'color: #2563eb; background-color: #eff6ff; border: 1px solid #bfdbfe;';
                $iconSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; display: inline-block; vertical-align: middle;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';
            }

            $rows[] = '<tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 12px 16px; text-align: center; font-size: 13px; color: #4b5563;">' . ($index + 1) . '</td>
                <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: #111827;">' . e($judul) . '</td>
                <td style="padding: 12px 16px; text-align: center;">' . $formatBadge . '</td>
                <td style="padding: 12px 16px; text-align: center; white-space: nowrap;">
                    <a href="' . e($url) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 12px; font-size: 12px; font-weight: 600; ' . $btnStyle . ' border-radius: 6px; text-decoration: none;">' .
                        $iconSvg .
                        '<span style="margin-left: 3px;">' . e($btnLabel) . '</span>' .
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
    }
}
