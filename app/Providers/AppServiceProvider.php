<?php

namespace App\Providers;

use App\Models\DokumenPengeluaran;
use App\Models\Pembayaran;
use App\Models\Pengesahan;
use App\Models\Verifikasi;
use App\Observers\AuditTrailObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DokumenPengeluaran::observe(AuditTrailObserver::class);
        Verifikasi::observe(AuditTrailObserver::class);
        Pengesahan::observe(AuditTrailObserver::class);
        Pembayaran::observe(AuditTrailObserver::class);
    }
}
