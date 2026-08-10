<x-filament-widgets::widget class="fi-wi-welcome">
    <div class="pitch-welcome-card">
        <div class="pitch-welcome-left">
            <h2 class="pitch-welcome-title">
                Hi, {{ auth()->user()?->name }}
            </h2>
            <p class="pitch-welcome-subtitle">
                @if(auth()->user()?->hasRole('pptk'))
                    Siap untuk mengelola dan mengajukan dokumen pengeluaran BLUD hari ini?
                @elseif(auth()->user()?->hasRole('verifikator'))
                    Siap untuk memeriksa dan memverifikasi antrean dokumen pengeluaran BLUD hari ini?
                @elseif(auth()->user()?->hasRole('ppk'))
                    Siap untuk meninjau dan mengesahkan dokumen pengeluaran BLUD hari ini?
                @elseif(auth()->user()?->hasRole('bendahara'))
                    Siap untuk memproses pembayaran dan pencairan SPJ dokumen BLUD hari ini?
                @else
                    Siap untuk memantau dan mengelola alur kerja verifikasi dokumen BLUD hari ini?
                @endif
            </p>
        </div>

        <div class="pitch-welcome-right">
            <img src="{{ asset('js/filament/widgets/components/undraw_budgeting_klon.svg') }}" alt="Budgeting Illustration" class="pitch-illustration">
        </div>
    </div>
</x-filament-widgets::widget>
