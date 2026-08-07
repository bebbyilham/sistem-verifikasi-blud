<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PengeluaranChart extends ChartWidget
{
    protected ?string $heading = 'Total Pengeluaran (Bulan Ini)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = [];
        $sums = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('d M');
            $sums[] = \App\Models\DokumenPengeluaran::whereDate('created_at', $date->toDateString())
                ->whereIn('status', ['sah', 'cair'])
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
