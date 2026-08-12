<?php

namespace App\Filament\Widgets;

use App\Models\DokumenPengeluaran;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class DokumenChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Pengajuan Dokumen';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin', 'manajemen', 'pptk', 'verifikator']);
    }

    protected function getData(): array
    {
        $days = [];
        $counts = [];

        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $start = $startDate ? \Carbon\Carbon::parse($startDate) : now()->subDays(6);
        $end = $endDate ? \Carbon\Carbon::parse($endDate) : now();

        $diffInDays = $start->diffInDays($end);

        if ($diffInDays > 90) {
            $period = \Carbon\CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->endOfMonth());
            foreach ($period as $date) {
                $days[] = $date->format('M Y');
                $counts[] = DokumenPengeluaran::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
        } else {
            $period = \Carbon\CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $days[] = $date->format('d M');
                $counts[] = DokumenPengeluaran::whereDate('created_at', $date->toDateString())->count();
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Dokumen Baru',
                    'data' => $counts,
                    'borderColor' => '#6366f1',
                    'fill' => true,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'tension' => 0.4,
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
