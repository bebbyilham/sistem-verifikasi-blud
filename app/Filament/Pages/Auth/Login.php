<?php

namespace App\Filament\Pages\Auth;

use App\Models\DokumenPengeluaran;
use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    public function getRecentDocuments()
    {
        return DokumenPengeluaran::with('bidang')
            ->latest('tanggal_ajuan')
            ->latest('id')
            ->take(3)
            ->get();
    }

    public function getStatsData(): array
    {
        $total = DokumenPengeluaran::count();
        $verified = DokumenPengeluaran::whereIn('status', [
            DokumenPengeluaran::STATUS_DIVERIFIKASI,
            DokumenPengeluaran::STATUS_DISAHKAN,
            DokumenPengeluaran::STATUS_DIBAYAR,
            DokumenPengeluaran::STATUS_DIARSIPKAN,
        ])->count();

        $percentage = $total > 0 ? round(($verified / $total) * 100, 1) : 0;

        return [
            'total' => $total,
            'rate' => $percentage . '%',
        ];
    }
}
