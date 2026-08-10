<?php

namespace App\Filament\Widgets;

use App\Models\DokumenPengeluaran;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        $user = auth()->user();

        if ($user && $user->hasRole('pptk')) {
            return 4;
        }

        if ($user && ($user->hasRole('verifikator') || $user->hasRole('bendahara'))) {
            return 3;
        }

        if ($user && $user->hasRole('ppk')) {
            return 2;
        }

        return 3;
    }

    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['admin', 'super_admin', 'manajemen'])) {
            return $this->getAdminStats();
        }

        if ($user->hasRole('pptk')) {
            return $this->getPptkStats();
        }

        if ($user->hasRole('verifikator')) {
            return $this->getVerifikatorStats();
        }

        if ($user->hasRole('ppk')) {
            return $this->getPpkStats();
        }

        if ($user->hasRole('bendahara')) {
            return $this->getBendaharaStats();
        }

        return [];
    }

    private function getAdminStats(): array
    {
        return [
            Stat::make('Total Dokumen', DokumenPengeluaran::count())
                ->description('Semua dokumen yang masuk')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Menunggu Verifikasi', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIAJUKAN)->count())
                ->description('Belum diverifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Dikembalikan', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIKEMBALIKAN)->count())
                ->description('Perlu revisi oleh PPTK')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('Disahkan', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DISAHKAN)->count())
                ->description('Telah disetujui PPK')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Dibayar', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIBAYAR)->count())
                ->description('Sudah diproses Bendahara')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Diarsipkan', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIARSIPKAN)->count())
                ->description('Proses selesai')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('gray'),
        ];
    }

    private function getPptkStats(): array
    {
        $myDocs = DokumenPengeluaran::where('pptk_id', auth()->id());

        return [
            Stat::make('Dokumen Saya', (clone $myDocs)->count())
                ->description('Total dokumen yang saya ajukan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Diajukan', (clone $myDocs)->where('status', DokumenPengeluaran::STATUS_DIAJUKAN)->count())
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Dikembalikan', (clone $myDocs)->where('status', DokumenPengeluaran::STATUS_DIKEMBALIKAN)->count())
                ->description('Perlu revisi')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('Selesai', (clone $myDocs)->whereIn('status', [DokumenPengeluaran::STATUS_DIBAYAR, DokumenPengeluaran::STATUS_DIARSIPKAN])->count())
                ->description('Dibayar / Diarsipkan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }

    private function getVerifikatorStats(): array
    {
        return [
            Stat::make('Antrean Verifikasi', DokumenPengeluaran::whereIn('status', [DokumenPengeluaran::STATUS_DIAJUKAN, DokumenPengeluaran::STATUS_DIKEMBALIKAN])->count())
                ->description('Menunggu pemeriksaan')
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('warning'),
            Stat::make('Diverifikasi Hari Ini', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIVERIFIKASI)->whereDate('updated_at', today())->count())
                ->description('Lolos verifikasi hari ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Dikembalikan Hari Ini', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIKEMBALIKAN)->whereDate('updated_at', today())->count())
                ->description('Revisi hari ini')
                ->descriptionIcon('heroicon-m-arrow-uturn-left')
                ->color('danger'),
        ];
    }

    private function getPpkStats(): array
    {
        return [
            Stat::make('Menunggu Pengesahan', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIVERIFIKASI)->count())
                ->description('Perlu pengesahan Anda')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),
            Stat::make('Disahkan Bulan Ini', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DISAHKAN)->whereMonth('updated_at', now()->month)->count())
                ->description('Total pengesahan bulan berjalan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }

    private function getBendaharaStats(): array
    {
        return [
            Stat::make('Menunggu Pembayaran', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DISAHKAN)->count())
                ->description('Perlu proses pembayaran')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            Stat::make('Dibayar Bulan Ini', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIBAYAR)->whereMonth('updated_at', now()->month)->count())
                ->description('Total pembayaran bulan berjalan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Menunggu Arsip', DokumenPengeluaran::where('status', DokumenPengeluaran::STATUS_DIBAYAR)->count())
                ->description('Belum diarsipkan')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'),
        ];
    }
}
