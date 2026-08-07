<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\DokumenPengeluaran;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'manajemen']);
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Dokumen Pengeluaran', DokumenPengeluaran::count())
                ->description('Semua dokumen yang masuk')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Dokumen Disahkan (Lolos)', DokumenPengeluaran::where('status', 'sah')->count())
                ->description('Telah disetujui PPK')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Dokumen Perlu Revisi', DokumenPengeluaran::where('status', 'revisi')->count())
                ->description('Dikembalikan oleh verifikator')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('Dokumen Telah Dibayar', DokumenPengeluaran::where('status', 'dibayar')->count())
                ->description('Sudah diproses Bendahara')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
