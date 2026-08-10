<?php

namespace App\Filament\Widgets;

use App\Models\DokumenPengeluaran;
use App\Models\RiwayatKoreksi;
use App\Models\Bidang;
use Filament\Widgets\ChartWidget;

class KoreksiPerBidangChart extends ChartWidget
{
    protected ?string $heading = 'Tingkat Koreksi Dokumen per Bidang';
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin', 'manajemen']);
    }

    protected function getData(): array
    {
        $bidangs = Bidang::all();
        $labels = [];
        $counts = [];

        foreach ($bidangs as $bidang) {
            $labels[] = $bidang->nama_bidang;
            $counts[] = RiwayatKoreksi::whereHas('dokumenPengeluaran', function ($q) use ($bidang) {
                $q->where('bidang_id', $bidang->id);
            })->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Koreksi',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#ef4444', '#f97316', '#eab308',
                        '#22c55e', '#3b82f6', '#8b5cf6',
                    ],
                    'borderRadius' => 5,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
