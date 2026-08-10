<?php

namespace App\Filament\Widgets;

use App\Models\DokumenPengeluaran;
use App\Models\Verifikasi;
use Filament\Widgets\ChartWidget;

class WaktuVerifikasiChart extends ChartWidget
{
    protected ?string $heading = 'Rata-Rata Waktu Verifikasi (Hari)';
    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super_admin', 'manajemen', 'verifikator']);
    }

    protected function getData(): array
    {
        $days = [];
        $averages = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('d M');

            // Hitung rata-rata waktu antara tanggal_ajuan dan tanggal_verifikasi
            $verifikasis = Verifikasi::whereDate('tanggal_verifikasi', $date->toDateString())
                ->with('dokumenPengeluaran')
                ->get();

            if ($verifikasis->isEmpty()) {
                $averages[] = 0;
            } else {
                $totalDays = 0;
                $count = 0;
                foreach ($verifikasis as $v) {
                    if ($v->dokumenPengeluaran && $v->dokumenPengeluaran->tanggal_ajuan) {
                        $ajuan = \Carbon\Carbon::parse($v->dokumenPengeluaran->tanggal_ajuan);
                        $verif = \Carbon\Carbon::parse($v->tanggal_verifikasi);
                        $totalDays += $ajuan->diffInDays($verif);
                        $count++;
                    }
                }
                $averages[] = $count > 0 ? round($totalDays / $count, 1) : 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata (hari)',
                    'data' => $averages,
                    'borderColor' => '#10b981',
                    'fill' => true,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
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
