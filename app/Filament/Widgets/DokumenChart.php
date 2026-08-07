<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class DokumenChart extends ChartWidget
{
    protected ?string $heading = 'Pengajuan Dokumen';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $days = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('d M');
            $counts[] = \App\Models\DokumenPengeluaran::whereDate('created_at', $date->toDateString())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Dokumen Baru',
                    'data' => $counts,
                    'borderColor' => '#6366f1',
                    'fill' => true,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'tension' => 0.4, // smooth line
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
