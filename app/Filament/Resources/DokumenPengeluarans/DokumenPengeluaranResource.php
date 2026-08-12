<?php

namespace App\Filament\Resources\DokumenPengeluarans;

use App\Filament\Resources\DokumenPengeluarans\Pages\CreateDokumenPengeluaran;
use App\Filament\Resources\DokumenPengeluarans\Pages\EditDokumenPengeluaran;
use App\Filament\Resources\DokumenPengeluarans\Pages\ListDokumenPengeluarans;
use App\Filament\Resources\DokumenPengeluarans\Schemas\DokumenPengeluaranForm;
use App\Filament\Resources\DokumenPengeluarans\Tables\DokumenPengeluaransTable;
use App\Models\DokumenPengeluaran;
use BackedEnum;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DokumenPengeluaranResource extends Resource
{
    protected static ?string $model = DokumenPengeluaran::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static \UnitEnum|string|null $navigationGroup = 'Dokumen';

    protected static ?string $navigationLabel = 'Dokumen Pengeluaran';

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('pptk');
    }

    public static function form(Schema $schema): Schema
    {
        return DokumenPengeluaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DokumenPengeluaransTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                \Filament\Schemas\Components\Section::make('Timeline Status Dokumen')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('timeline_visual')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record) return '';

                                $currentStatus = $record->status;
                                $stages = [
                                    'diajukan' => ['label' => '1. Diajukan', 'desc' => 'Pengajuan PPTK'],
                                    'diverifikasi' => ['label' => '2. Diverifikasi', 'desc' => 'Checklist Verifikator'],
                                    'disahkan' => ['label' => '3. Disahkan', 'desc' => 'Approval PPK'],
                                    'dibayar' => ['label' => '4. Dibayar', 'desc' => 'SPJ Bendahara'],
                                    'diarsipkan' => ['label' => '5. Diarsipkan', 'desc' => 'Arsip Digital'],
                                ];

                                $isReturned = ($currentStatus === 'dikembalikan');

                                $statusOrder = [
                                    'diajukan' => 1,
                                    'dikembalikan' => 1, // at stage 1 waiting for re-submission
                                    'diverifikasi' => 2,
                                    'disahkan' => 3,
                                    'dibayar' => 4,
                                    'diarsipkan' => 5,
                                ];

                                $currentStep = $statusOrder[$currentStatus] ?? 1;

                                $html = '<div style="padding: 16px 8px; width: 100%; box-sizing: border-box;">';
                                if ($isReturned) {
                                    $html .= '<div style="margin-bottom: 16px; padding: 10px 16px; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #991b1b; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">' .
                                        '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' .
                                        ' Dokumen saat ini dalam status <strong>DIKEMBALIKAN (REVISI)</strong>. Silakan periksa Riwayat Koreksi di bawah.</div>';
                                }

                                $html .= '<div style="display: flex; align-items: center; justify-content: space-between; width: 100%; position: relative;">';

                                $count = count($stages);
                                $i = 0;
                                foreach ($stages as $key => $stage) {
                                    $i++;
                                    $stepNum = $statusOrder[$key];
                                    $isPassed = $currentStep > $stepNum;
                                    $isCurrent = $currentStep === $stepNum && !$isReturned;
                                    $isCurrentReturned = $isReturned && $key === 'diajukan';

                                    // Color logic
                                    $circleBg = '#e5e7eb';
                                    $circleColor = '#6b7280';
                                    $borderStyle = 'none';

                                    if ($isPassed) {
                                        $circleBg = '#10b981';
                                        $circleColor = '#ffffff';
                                    } elseif ($isCurrent) {
                                        $circleBg = '#6366f1';
                                        $circleColor = '#ffffff';
                                        $borderStyle = '0 0 0 4px rgba(99, 102, 241, 0.2)';
                                    } elseif ($isCurrentReturned) {
                                        $circleBg = '#ef4444';
                                        $circleColor = '#ffffff';
                                        $borderStyle = '0 0 0 4px rgba(239, 68, 68, 0.2)';
                                    }

                                    $html .= '<div style="display: flex; flex-direction: column; align-items: center; text-align: center; flex: 1; z-index: 2;">';
                                    $html .= '<div style="width: 40px; height: 40px; border-radius: 50%; background-color: ' . $circleBg . '; color: ' . $circleColor . '; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; box-shadow: ' . $borderStyle . '; transition: all 0.2s;">';
                                    if ($isPassed) {
                                        $html .= '✓';
                                    } else {
                                        $html .= $i;
                                    }
                                    $html .= '</div>';
                                    $html .= '<div style="margin-top: 8px; font-size: 13px; font-weight: 700; color: ' . ($isCurrent || $isPassed ? '#111827' : '#6b7280') . ';">' . e($stage['label']) . '</div>';
                                    $html .= '<div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">' . e($stage['desc']) . '</div>';
                                    $html .= '</div>';

                                    // Line between steps
                                    if ($i < $count) {
                                        $lineBg = $currentStep > $stepNum ? '#10b981' : '#e5e7eb';
                                        $html .= '<div style="flex: 1; height: 3px; background-color: ' . $lineBg . '; margin-top: -28px; z-index: 1;"></div>';
                                    }
                                }

                                $html .= '</div></div>';
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ]),
                \Filament\Schemas\Components\Section::make('Catatan Revisi / Pengembalian')
                    ->columnSpanFull()
                    ->visible(fn ($record) => $record?->status === DokumenPengeluaran::STATUS_DIKEMBALIKAN || $record?->riwayatKoreksis()->exists())
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('catatan_revisi_display')
                            ->label('')
                            ->content(function ($record) {
                                $koreksi = $record?->riwayatKoreksis()->latest()->first();
                                if (!$koreksi) {
                                    return new \Illuminate\Support\HtmlString('');
                                }

                                $jenisKesalahan = $koreksi->jenisKesalahan?->nama_kesalahan ?? 'Umum';
                                $catatan = $koreksi->catatan_koreksi ?? '-';
                                $verifikator = $koreksi->pengoreksi?->name ?? 'Verifikator';
                                $tanggal = $koreksi->tanggal_koreksi ? \Carbon\Carbon::parse($koreksi->tanggal_koreksi)->translatedFormat('d M Y H:i') : '-';

                                return new \Illuminate\Support\HtmlString('
                                    <div style="padding: 16px 20px; border-radius: 12px; background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                            <span style="font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 6px; color: #dc2626;">
                                                ⚠️ Dokumen Dikembalikan untuk Direvisi
                                            </span>
                                            <span style="font-size: 11px; color: #991b1b; background: #fee2e2; padding: 2px 8px; border-radius: 6px; font-weight: 600;">
                                                Versi ' . $koreksi->versi_ke . ' • ' . e($tanggal) . '
                                            </span>
                                        </div>
                                        <div style="font-size: 13px; margin-bottom: 6px; color: #7f1d1d;">
                                            <strong>Kategori Kesalahan:</strong> <span style="display: inline-block; padding: 1px 8px; background: #ffffff; border: 1px solid #fca5a5; border-radius: 4px; font-weight: 600; color: #b91c1c;">' . e($jenisKesalahan) . '</span>
                                        </div>
                                        <div style="font-size: 13px; color: #7f1d1d; line-height: 1.5; background: #ffffff; padding: 10px 14px; border-radius: 8px; border: 1px solid #fecaca;">
                                            <strong>Catatan Verifikator (' . e($verifikator) . '):</strong><br>
                                            ' . nl2br(e($catatan)) . '
                                        </div>
                                    </div>
                                ');
                            })
                            ->columnSpanFull(),
                    ]),
                \Filament\Schemas\Components\Section::make('Detail Dokumen Pengeluaran')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(3)
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('kode_dokumen')
                                    ->label('Kode Dokumen')
                                    ->weight('bold'),
                                \Filament\Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        \App\Models\DokumenPengeluaran::STATUS_DIAJUKAN => 'warning',
                                        \App\Models\DokumenPengeluaran::STATUS_DIVERIFIKASI => 'info',
                                        \App\Models\DokumenPengeluaran::STATUS_DIKEMBALIKAN => 'danger',
                                        \App\Models\DokumenPengeluaran::STATUS_DISAHKAN => 'success',
                                        \App\Models\DokumenPengeluaran::STATUS_DIBAYAR => 'success',
                                        \App\Models\DokumenPengeluaran::STATUS_DIARSIPKAN => 'gray',
                                        default => 'gray',
                                    }),
                                \Filament\Infolists\Components\TextEntry::make('tanggal_ajuan')
                                    ->label('Tanggal Pengajuan')
                                    ->date('d M Y'),
                                \Filament\Infolists\Components\TextEntry::make('bidang.nama_bidang')
                                    ->label('Bidang / Unit Kerja'),
                                \Filament\Infolists\Components\TextEntry::make('jenis_dokumen')
                                    ->label('Jenis Dokumen'),
                                \Filament\Infolists\Components\TextEntry::make('sumber_dana')
                                    ->label('Sumber Dana')
                                    ->badge(),
                                \Filament\Infolists\Components\TextEntry::make('nominal')
                                    ->label('Nominal Pengajuan')
                                    ->formatStateUsing(fn ($state) => filled($state) ? 'Rp ' . number_format((float) $state, 0, ',', '.') : '-'),
                                \Filament\Infolists\Components\TextEntry::make('keterangan')
                                    ->label('Keterangan')
                                    ->placeholder('Tidak ada keterangan.')
                                    ->columnSpanFull(),
                            ]),
                    ]),
                \Filament\Schemas\Components\Section::make('Dokumen Lampiran (PDF)')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('file_path_display')
                            ->label('')
                            ->content(function ($record) {
                                $files = $record?->file_path;
                                if (is_string($files)) {
                                    $files = json_decode($files, true) ?? [];
                                }
                                $files = array_filter((array) $files);

                                if (empty($files)) {
                                    return new \Illuminate\Support\HtmlString('<div style="padding: 16px; text-align: center; font-size: 13px; color: #6b7280; font-style: italic; background-color: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">Tidak ada file lampiran.</div>');
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
                                        <td style="padding: 12px 16px; text-align: center; font-size: 13px; color: #4b5563; width: 60px;">' . ($index + 1) . '</td>
                                        <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: #111827;">' . e($judul) . '</td>
                                        <td style="padding: 12px 16px; text-align: center; width: 100px;">' . $formatBadge . '</td>
                                        <td style="padding: 12px 16px; text-align: center; width: 140px; white-space: nowrap;">
                                            <a href="' . e($url) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 12px; font-size: 12px; font-weight: 600; ' . $btnStyle . ' border-radius: 6px; text-decoration: none;">' .
                                                $iconSvg .
                                                '<span style="margin-left: 3px;">' . e($btnLabel) . '</span>' .
                                            '</a>' .
                                        '</td>' .
                                    '</tr>';
                                }

                                return new \Illuminate\Support\HtmlString('<div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); max-width: 100%;">' .
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
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\DokumenPengeluarans\RelationManagers\VerifikasisRelationManager::class,
            \App\Filament\Resources\DokumenPengeluarans\RelationManagers\RiwayatKoreksisRelationManager::class,
            \App\Filament\Resources\DokumenPengeluarans\RelationManagers\PengesahansRelationManager::class,
            \App\Filament\Resources\DokumenPengeluarans\RelationManagers\PembayaransRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDokumenPengeluarans::route('/'),
            'edit' => EditDokumenPengeluaran::route('/{record}/edit'),
            'view' => \App\Filament\Resources\DokumenPengeluarans\Pages\ViewDokumenPengeluaran::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('pptk')) {
            $query->where('pptk_id', $user->id);
        }

        // Rekanan hanya melihat dokumen yang sudah dibayar/diarsipkan (read-only view)
        if ($user->hasRole('rekanan')) {
            $query->whereIn('status', [
                DokumenPengeluaran::STATUS_DIBAYAR,
                DokumenPengeluaran::STATUS_DIARSIPKAN,
            ]);
        }

        // Admin, Super Admin, Verifikator, PPK, Bendahara, Manajemen
        // melihat seluruh dokumen, di mana filter status ditangani oleh Tab & Table Filter.

        return $query;
    }
}
