<div class="split-login-container">
    <!-- Left Side: Login Form -->
    <div class="split-login-left">
        <div class="split-login-form-box">
            <!-- Brand Logo Header -->
            <div class="split-brand-header">
                <div class="split-brand-logo">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <span class="split-brand-title">Sistem Verifikasi BLUD</span>
            </div>

            <!-- Login Welcome Heading -->
            <div class="split-heading-box">
                <h1 class="split-heading-title">Hi, Selamat Datang</h1>
                <p class="split-heading-subtitle">Masuk untuk mengelola & memverifikasi dokumen pengeluaran BLUD</p>
            </div>

            <!-- Filament Livewire Login Form -->
            <form wire:submit="authenticate" class="split-filament-form">
                {{ $this->form }}

                <div class="split-form-actions">
                    <button type="submit" class="split-submit-btn">
                        <span>Login ke Sistem</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side: Application Dashboard Feature Showcase (Exact Match to Application Dashboard) -->
    <div class="split-login-right">
        <div class="split-hero-glow-top"></div>
        <div class="split-hero-glow-bottom"></div>

        <div class="split-hero-content">
            <h2 class="split-hero-title">
                Sistem Verifikasi & Pengesahan Dokumen BLUD
            </h2>
            <p class="split-hero-subtitle">
                Sistem terpadu pengajuan SPJ, verifikasi berjenjang multi-role, dan pengawasan real-time berstandar akuntansi BLUD.
            </p>

            <!-- Dashboard Application Window Mockup (Exact 1:1 Match to App Dashboard) -->
            <div class="app-dashboard-window">
                <!-- App Dashboard Content Preview (Exact 1:1 Replica of Application Dashboard) -->
                <div class="app-window-body">
                    <div class="app-dasbor-title">Dasbor</div>

                    <!-- 1. Pitch.io Welcome Card Widget (Exact 1:1 Replica of welcome-widget.blade.php) -->
                    <div class="pitch-welcome-card mini-welcome" style="margin-bottom: 14px;">
                        <div class="pitch-welcome-left">
                            <h2 class="pitch-welcome-title">
                                Hi, Admin Keuangan
                            </h2>
                            <p class="pitch-welcome-subtitle">
                                Siap untuk memantau dan mengelola alur kerja verifikasi dokumen BLUD hari ini?
                            </p>
                        </div>

                        <div class="pitch-welcome-right">
                            <img src="{{ asset('js/filament/widgets/components/undraw_budgeting_klon.svg') }}" alt="Budgeting Illustration" class="pitch-illustration">
                        </div>
                    </div>

                    <!-- 2. Filter Pill Bar Mockup (Exact 1:1 Replica of Dashboard Filter Toolbar) -->
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 14px; width: 100%;">
                        <!-- Left Pill Group: Timeline / List -->
                        <div style="display: inline-flex; align-items: center; padding: 3px; background: rgba(255, 255, 255, 0.85); border-radius: 10px; border: 1px solid rgba(226, 232, 240, 0.9); gap: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                            <span style="padding: 4px 12px; font-size: 11px; font-weight: 700; color: #0f172a; background: #ffffff; border-radius: 7px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">Timeline</span>
                            <span style="padding: 4px 12px; font-size: 11px; font-weight: 500; color: #64748b;">List</span>
                        </div>

                        <!-- Right Pill Group: Period Presets -->
                        <div style="display: inline-flex; align-items: center; padding: 3px; background: rgba(255, 255, 255, 0.85); border-radius: 10px; border: 1px solid rgba(226, 232, 240, 0.9); gap: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                            <span style="padding: 4px 8px; font-size: 11px; font-weight: 500; color: #64748b;">1D</span>
                            <span style="padding: 4px 8px; font-size: 11px; font-weight: 700; color: #0f172a; background: #ffffff; border-radius: 7px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">7D</span>
                            <span style="padding: 4px 8px; font-size: 11px; font-weight: 500; color: #64748b;">1M</span>
                            <span style="padding: 4px 8px; font-size: 11px; font-weight: 500; color: #64748b;">3M</span>
                            <span style="padding: 4px 8px; font-size: 11px; font-weight: 500; color: #64748b;">YTD</span>
                            <span style="padding: 4px 6px; font-size: 11px; color: #64748b; display: inline-flex; align-items: center;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </span>
                            <span style="padding: 4px 8px; font-size: 11px; font-weight: 500; color: #64748b;">All time</span>
                        </div>
                    </div>

                    <!-- 3. Stat Cards Grid (6 Floating White Cards) -->
                    <div class="app-stats-grid">
                        <div class="app-stat-card">
                            <span class="app-stat-label">Total Dokumen</span>
                            <span class="app-stat-value">8</span>
                            <span class="app-stat-sub text-blue">Semua dokumen yang masuk 📄</span>
                        </div>
                        <div class="app-stat-card">
                            <span class="app-stat-label">Menunggu Verifikasi</span>
                            <span class="app-stat-value">3</span>
                            <span class="app-stat-sub text-orange">Belum diverifikasi 🕒</span>
                        </div>
                        <div class="app-stat-card">
                            <span class="app-stat-label">Dikembalikan</span>
                            <span class="app-stat-value">2</span>
                            <span class="app-stat-sub text-red">Perlu revisi oleh PPTK ✖</span>
                        </div>
                        <div class="app-stat-card">
                            <span class="app-stat-label">Disahkan</span>
                            <span class="app-stat-value">1</span>
                            <span class="app-stat-sub text-green">Telah disetujui PPK ✔</span>
                        </div>
                        <div class="app-stat-card">
                            <span class="app-stat-label">Dibayar</span>
                            <span class="app-stat-value">0</span>
                            <span class="app-stat-sub text-emerald">Sudah diproses Bendahara 💲</span>
                        </div>
                        <div class="app-stat-card">
                            <span class="app-stat-label">Diarsipkan</span>
                            <span class="app-stat-value">1</span>
                            <span class="app-stat-sub text-gray">Proses selesai 🗄️</span>
                        </div>
                    </div>

                    <!-- 3. Charts Row Grid -->
                    <div class="app-charts-grid">
                        <div class="app-chart-card">
                            <span class="app-chart-title">Total Pengeluaran (7 Hari Terakhir)</span>
                            <div class="app-bar-chart-preview">
                                <div class="app-bar-col"><span></span><small>04 Aug</small></div>
                                <div class="app-bar-col"><span></span><small>06 Aug</small></div>
                                <div class="app-bar-col"><span></span><small>08 Aug</small></div>
                                <div class="app-bar-col bar-active"><span style="height: 85%;"></span><small>10 Aug</small></div>
                            </div>
                        </div>
                        <div class="app-chart-card">
                            <span class="app-chart-title">Pengajuan Dokumen</span>
                            <div class="app-line-chart-preview">
                                <svg viewBox="0 0 200 50" class="app-line-svg">
                                    <path d="M10,45 L40,45 L70,45 L100,45 L130,45 L160,45 L190,8" fill="none" stroke="#6366f1" stroke-width="2.5" />
                                    <circle cx="190" cy="8" r="3.5" fill="#6366f1" />
                                </svg>
                                <div class="app-line-dates">
                                    <span>04 Aug</span><span>06 Aug</span><span>08 Aug</span><span>10 Aug</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
