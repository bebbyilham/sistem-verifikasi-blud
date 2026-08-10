<?php

namespace App\Filament\Widgets;

use App\Models\DokumenPengeluaran;
use Filament\Widgets\ChartWidget;

class PengeluaranChart extends ChartWidget
{
    protected ?string $heading = 'Total Pengeluaran (7 Hari Terakhir)';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin', 'manajemen']);
    }

    protected function getData(): array
    {
        $days = [];
        $sums = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('d M');
            $sums[] = DokumenPengeluaran::whereDate('created_at', $date->toDateString())
                ->whereIn('status', [
                    DokumenPengeluaran::STATUS_DISAHKAN,
                    DokumenPengeluaran::STATUS_DIBAYAR,
                    DokumenPengeluaran::STATUS_DIARSIPKAN,
                ])
                ->sum('nominal') / 1000000; // dalam jutaan
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran Sah (Jutaan)',
                    'data' => $sums,
                    'backgroundColor' => '#6366f1',
                    'borderRadius' => 5,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
