<?php

namespace App\Filament\Widgets;

use App\Models\DokumenPengeluaran;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PengeluaranChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Total Pengeluaran';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin', 'manajemen']);
    }

    protected function getData(): array
    {
        $days = [];
        $sums = [];

        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $start = $startDate ? \Carbon\Carbon::parse($startDate) : now()->subDays(6);
        $end = $endDate ? \Carbon\Carbon::parse($endDate) : now();

        $diffInDays = $start->diffInDays($end);

        if ($diffInDays > 90) {
            $period = \Carbon\CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->endOfMonth());
            foreach ($period as $date) {
                $days[] = $date->format('M Y');
                $sums[] = DokumenPengeluaran::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->whereIn('status', [
                        DokumenPengeluaran::STATUS_DISAHKAN,
                        DokumenPengeluaran::STATUS_DIBAYAR,
                        DokumenPengeluaran::STATUS_DIARSIPKAN,
                    ])
                    ->sum('nominal') / 1000000;
            }
        } else {
            $period = \Carbon\CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $days[] = $date->format('d M');
                $sums[] = DokumenPengeluaran::whereDate('created_at', $date->toDateString())
                    ->whereIn('status', [
                        DokumenPengeluaran::STATUS_DISAHKAN,
                        DokumenPengeluaran::STATUS_DIBAYAR,
                        DokumenPengeluaran::STATUS_DIARSIPKAN,
                    ])
                    ->sum('nominal') / 1000000;
            }
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
