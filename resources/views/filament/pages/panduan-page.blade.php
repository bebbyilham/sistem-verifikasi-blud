<x-filament-panels::page>
    <div style="font-family: inherit;">

        <!-- Custom Page Title Banner -->
        <div style="margin-bottom: 24px;">
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 6px; letter-spacing: -0.01em;">
                Panduan Penggunaan &amp; Diagram Arsitektur Sistem
            </h1>
            <p style="font-size: 13.5px; color: #64748b; margin: 0;">
                Dokumentasi operasional 5 siklus dokumen pengeluaran BLUD dan visualisasi arsitektur 7-in-1.
            </p>
        </div>

        <!-- Navigation Tabs Bar -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0; margin-bottom: 24px;">
            <button 
                type="button"
                wire:click="setTab('pemakaian')" 
                style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; border: 1px solid; cursor: pointer; transition: all 0.2s ease; {{ $activeTab === 'pemakaian' ? 'background: #4f46e5; color: #ffffff; border-color: #4338ca; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);' : 'background: #ffffff; color: #475569; border-color: #cbd5e1;' }}"
            >
                <span style="font-size: 16px;">📘</span>
                <span>Cara Pemakaian Aplikasi</span>
            </button>

            <button 
                type="button"
                wire:click="setTab('diagram')" 
                style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; border: 1px solid; cursor: pointer; transition: all 0.2s ease; {{ $activeTab === 'diagram' ? 'background: #4f46e5; color: #ffffff; border-color: #4338ca; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);' : 'background: #ffffff; color: #475569; border-color: #cbd5e1;' }}"
            >
                <span style="font-size: 16px;">📊</span>
                <span>Diagram Arsitektur Sistem</span>
            </button>

            <a 
                href="{{ url('/diagrams/') }}" 
                target="_blank" 
                style="margin-left: auto; display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 10px; background: #0f172a; color: #ffffff; font-size: 13px; font-weight: 600; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.1); transition: all 0.2s ease;"
            >
                <span>🔗 Buka Diagram Tab Baru ↗</span>
            </a>
        </div>

        <!-- ============================================================== -->
        <!-- TAB 1: CARA PEMAKAIAN APLIKASI                                 -->
        <!-- ============================================================== -->
        @if($activeTab === 'pemakaian')
            <div>
                <!-- Banner Header -->
                <div style="position: relative; overflow: hidden; border-radius: 16px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%); padding: 28px; color: #ffffff; box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4); margin-bottom: 28px;">
                    <span style="display: inline-block; padding: 4px 12px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #ffffff; margin-bottom: 8px;">
                        Sistem Verifikasi Dokumen Pengeluaran BLUD
                    </span>
                    <h2 style="font-size: 24px; font-weight: 800; margin: 6px 0 10px; color: #ffffff; line-height: 1.25; letter-spacing: -0.01em;">
                        Panduan Operasional &amp; Tatacara Penggunaan
                    </h2>
                    <p style="font-size: 14px; line-height: 1.6; color: rgba(238, 242, 255, 0.92); max-width: 720px; margin: 0;">
                        Aplikasi ini dirancang untuk mengelola dan memverifikasi seluruh alur pengajuan dokumen pengeluaran BLUD secara transparan, terintegrasi, dan realtime dari PPTK hingga Bendahara Pengeluaran.
                    </p>
                </div>

                <!-- Ringkasan 5 Fase Utama Alur Dokumen -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0 0 16px;">
                        <span style="font-size: 20px;">🔄</span>
                        <span>5 Fase Utama Siklus Hidup Dokumen Pengeluaran</span>
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                        <!-- Fase 1 -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59,130,246,0.12); color: #2563eb; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                1
                            </div>
                            <h4 style="font-weight: 700; font-size: 14px; margin: 0 0 6px; color: #0f172a;">Pengajuan (PPTK)</h4>
                            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                                PPTK membuat dokumen pengeluaran baru, melengkapi nominal &amp; lampiran file. Status diawali <span style="font-family: monospace; font-weight: 700; background: rgba(59,130,246,0.12); color: #2563eb; padding: 2px 6px; border-radius: 4px; font-size: 11px;">diajukan</span>.
                            </p>
                        </div>

                        <!-- Fase 2 -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(147,51,234,0.12); color: #9333ea; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                2
                            </div>
                            <h4 style="font-weight: 700; font-size: 14px; margin: 0 0 6px; color: #0f172a;">Verifikasi</h4>
                            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                                Verifikator memeriksa berkas. Jika sesuai &rarr; <span style="font-family: monospace; font-weight: 700; background: rgba(147,51,234,0.12); color: #9333ea; padding: 2px 6px; border-radius: 4px; font-size: 11px;">diverifikasi</span>. Jika salah &rarr; <span style="font-family: monospace; font-weight: 700; background: rgba(239,68,68,0.12); color: #ef4444; padding: 2px 6px; border-radius: 4px; font-size: 11px;">dikembalikan</span>.
                            </p>
                        </div>

                        <!-- Fase 3 -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(249,115,22,0.12); color: #ea580c; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                3
                            </div>
                            <h4 style="font-weight: 700; font-size: 14px; margin: 0 0 6px; color: #0f172a;">Pengesahan (PPK)</h4>
                            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                                PPK menyahkan dokumen yang sudah lolos verifikasi. Status diperbarui menjadi <span style="font-family: monospace; font-weight: 700; background: rgba(249,115,22,0.12); color: #ea580c; padding: 2px 6px; border-radius: 4px; font-size: 11px;">disahkan</span>.
                            </p>
                        </div>

                        <!-- Fase 4 -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(16,185,129,0.12); color: #059669; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                4
                            </div>
                            <h4 style="font-weight: 700; font-size: 14px; margin: 0 0 6px; color: #0f172a;">Pembayaran (Bendahara)</h4>
                            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                                Bendahara memproses SPJ &amp; nomor bukti bayar. Status berubah menjadi <span style="font-family: monospace; font-weight: 700; background: rgba(16,185,129,0.12); color: #059669; padding: 2px 6px; border-radius: 4px; font-size: 11px;">dibayar</span>.
                            </p>
                        </div>

                        <!-- Fase 5 -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(100,116,139,0.12); color: #64748b; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                5
                            </div>
                            <h4 style="font-weight: 700; font-size: 14px; margin: 0 0 6px; color: #0f172a;">Pengarsipan</h4>
                            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                                Dokumen yang telah selesai dibayar disimpan permanen di arsip digital sistem dengan status <span style="font-family: monospace; font-weight: 700; background: rgba(100,116,139,0.12); color: #64748b; padding: 2px 6px; border-radius: 4px; font-size: 11px;">diarsipkan</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Panduan Berdasarkan Peran (Role Guides) -->
                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0 0 16px;">
                        <span style="font-size: 20px;">👥</span>
                        <span>Panduan Khusus Berdasarkan Peran Pengguna</span>
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
                        
                        <!-- Role PPTK -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; background: rgba(16,185,129,0.12); color: #059669; margin-bottom: 10px;">
                                📝 PPTK
                            </div>
                            <h4 style="font-size: 15px; font-weight: 700; margin: 0 0 2px; color: #0f172a;">Pejabat Pelaksana Teknis Kegiatan</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 12px;">Pembuat dan penanggung jawab dokumen kegiatan bidang</p>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.5;">
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #10b981; font-weight: bold;">✓</span>
                                    <span><strong>Membuat Dokumen Baru:</strong> Masuk ke <em>Dokumen Pengeluaran &rarr; Tambah Dokumen</em>, pilih jenis dokumen, sumber dana, isi nominal, dan upload lampiran.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #10b981; font-weight: bold;">✓</span>
                                    <span><strong>Mengecek Revisi:</strong> Jika status dokumen <span style="font-family: monospace; font-weight: 700; background: rgba(239,68,68,0.12); color: #ef4444; padding: 2px 6px; border-radius: 4px; font-size: 11px;">dikembalikan</span>, lihat tab <em>Riwayat Koreksi</em> di detail dokumen.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #10b981; font-weight: bold;">✓</span>
                                    <span><strong>Mengirim Ulang Revisi:</strong> Edit dokumen, upload lampiran perbaikan, lalu submit ulang. Versi koreksi bertambah otomatis.</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Role Verifikator -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; background: rgba(147,51,234,0.12); color: #9333ea; margin-bottom: 10px;">
                                🔍 Verifikator
                            </div>
                            <h4 style="font-size: 15px; font-weight: 700; margin: 0 0 2px; color: #0f172a;">Pemeriksa &amp; Verifikator Dokumen</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 12px;">Penilai kelayakan administrasi &amp; keabsahan kelengkapan berkas</p>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.5;">
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #9333ea; font-weight: bold;">✓</span>
                                    <span><strong>Review Dokumen Masuk:</strong> Membuka dokumen berstatus <span style="font-family: monospace; font-weight: 700; background: rgba(59,130,246,0.12); color: #2563eb; padding: 2px 6px; border-radius: 4px; font-size: 11px;">diajukan</span> dari daftar dokumen atau notifikasi.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #9333ea; font-weight: bold;">✓</span>
                                    <span><strong>Hasil Lolos:</strong> Jika valid, pilih <em>Lolos</em>. Status dokumen menjadi <span style="font-family: monospace; font-weight: 700; background: rgba(147,51,234,0.12); color: #9333ea; padding: 2px 6px; border-radius: 4px; font-size: 11px;">diverifikasi</span> dan terkirim ke PPK.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #9333ea; font-weight: bold;">✓</span>
                                    <span><strong>Hasil Dikembalikan:</strong> Jika ada kesalahan, pilih <em>Dikembalikan</em>, tentukan kategori kesalahan, dan isi catatan koreksi spesifik.</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Role PPK -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; background: rgba(249,115,22,0.12); color: #ea580c; margin-bottom: 10px;">
                                ✅ PPK
                            </div>
                            <h4 style="font-size: 15px; font-weight: 700; margin: 0 0 2px; color: #0f172a;">Pejabat Pembuat Komitmen</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 12px;">Pengesah komitmen pengeluaran anggaran BLUD</p>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.5;">
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #ea580c; font-weight: bold;">✓</span>
                                    <span><strong>Pengesahan Dokumen:</strong> Buka dokumen berstatus <span style="font-family: monospace; font-weight: 700; background: rgba(147,51,234,0.12); color: #9333ea; padding: 2px 6px; border-radius: 4px; font-size: 11px;">diverifikasi</span>, periksa kesesuaian pagu anggaran, lalu klik tombol Pengesahan.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #ea580c; font-weight: bold;">✓</span>
                                    <span>Status berubah ke <span style="font-family: monospace; font-weight: 700; background: rgba(249,115,22,0.12); color: #ea580c; padding: 2px 6px; border-radius: 4px; font-size: 11px;">disahkan</span> dan sistem mengirimkan notifikasi ke Bendahara Pengeluaran.</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Role Bendahara -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; background: rgba(59,130,246,0.12); color: #2563eb; margin-bottom: 10px;">
                                💰 Bendahara
                            </div>
                            <h4 style="font-size: 15px; font-weight: 700; margin: 0 0 2px; color: #0f172a;">Bendahara Pengeluaran</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 12px;">Pemproses pencairan dana &amp; penerbitan nomor SPJ</p>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.5;">
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #2563eb; font-weight: bold;">✓</span>
                                    <span><strong>Pencairan Dana:</strong> Buka dokumen berstatus <span style="font-family: monospace; font-weight: 700; background: rgba(249,115,22,0.12); color: #ea580c; padding: 2px 6px; border-radius: 4px; font-size: 11px;">disahkan</span>, inputkan nomor SPJ, tanggal pencairan, dan upload bukti transfer.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #2563eb; font-weight: bold;">✓</span>
                                    <span>Status dokumen menjadi <span style="font-family: monospace; font-weight: 700; background: rgba(16,185,129,0.12); color: #059669; padding: 2px 6px; border-radius: 4px; font-size: 11px;">dibayar</span> dan grafik analytics dashboard ter-update secara otomatis.</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Role Manajemen -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; background: rgba(245,158,11,0.12); color: #d97706; margin-bottom: 10px;">
                                📈 Manajemen
                            </div>
                            <h4 style="font-size: 15px; font-weight: 700; margin: 0 0 2px; color: #0f172a;">Manajemen / Direksi Rumah Sakit</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 12px;">Monitoring komprehensif real-time &amp; audit trail transparan</p>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.5;">
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #d97706; font-weight: bold;">✓</span>
                                    <span><strong>Monitoring Realtime:</strong> Memantau 5 chart analitik: Tren Dokumen, Pengeluaran per Bidang, Kategori Koreksi, dan Rata-rata Waktu Verifikasi.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #d97706; font-weight: bold;">✓</span>
                                    <span><strong>Audit Trail:</strong> Membuka menu <em>Audit Trail</em> untuk melacak seluruh aktivitas user (nama, role, waktu, aksi, IP address).</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Role Admin -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; background: rgba(100,116,139,0.12); color: #475569; margin-bottom: 10px;">
                                👑 Admin
                            </div>
                            <h4 style="font-size: 15px; font-weight: 700; margin: 0 0 2px; color: #0f172a;">Administrator Sistem</h4>
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 12px;">Pengelola data master, user, bidang, &amp; manajemen hak akses</p>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; color: #334155; line-height: 1.5;">
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #475569; font-weight: bold;">✓</span>
                                    <span><strong>Kelola Data Master:</strong> Mengelola Master Bidang, Master Jenis Dokumen, Master Jenis Kesalahan, dan User.</span>
                                </li>
                                <li style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="color: #475569; font-weight: bold;">✓</span>
                                    <span><strong>Manajemen Permission:</strong> Menentukan hak akses granular tiap role menggunakan Filament Shield.</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <!-- Pertanyaan Sering Diajukan (FAQ) -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 32px;">
                    <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0 0 16px;">
                        <span style="font-size: 20px;">❓</span>
                        <span>Pertanyaan Sering Diajukan (FAQ)</span>
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <p style="font-weight: 700; font-size: 13.5px; margin: 0 0 4px; color: #0f172a;">Q: Bagaimana jika dokumen saya dikembalikan oleh Verifikator?</p>
                            <p style="font-size: 12.5px; color: #64748b; line-height: 1.6; margin: 0;">
                                Buka detail dokumen Anda, lalu cek tab <em>Riwayat Koreksi</em>. Di sana tercantum penyebab pengembalian dan catatan spesifik. Lakukan perbaikan data/lampiran dan klik simpan. Status dokumen akan otomatis berubah kembali menjadi <span style="font-family: monospace; font-weight: 700; background: rgba(59,130,246,0.12); color: #2563eb; padding: 2px 6px; border-radius: 4px; font-size: 11px;">diajukan</span> untuk diverifikasi ulang.
                            </p>
                        </div>

                        <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                            <p style="font-weight: 700; font-size: 13.5px; margin: 0 0 4px; color: #0f172a;">Q: Bagaimana cara melacak riwayat perubahan status dokumen?</p>
                            <p style="font-size: 12.5px; color: #64748b; line-height: 1.6; margin: 0;">
                                Setiap dokumen dilengkapi dengan <strong>Timeline Status Visual</strong> pada halaman detailnya. Anda dapat melihat tanggal dan waktu persis kapan dokumen diajukan, diverifikasi, disahkan, hingga dibayar.
                            </p>
                        </div>

                        <div>
                            <p style="font-weight: 700; font-size: 13.5px; margin: 0 0 4px; color: #0f172a;">Q: Di mana saya bisa melihat seluruh notifikasi sistem?</p>
                            <p style="font-size: 12.5px; color: #64748b; line-height: 1.6; margin: 0;">
                                Klik ikon <strong>Lonceng Notifikasi</strong> di pojok kanan atas Filament Panel. Notifikasi akan muncul secara realtime ketika ada dokumen baru yang memerlukan tindakan Anda.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        <!-- ============================================================== -->
        <!-- TAB 2: DIAGRAM ARSITEKTUR SISTEM                               -->
        <!-- ============================================================== -->
        @if($activeTab === 'diagram')
            <div>
                <!-- Information Card -->
                <div style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: 14px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 22px;">ℹ️</span>
                        <div>
                            <p style="font-weight: 700; font-size: 14px; margin: 0 0 2px; color: #1e40af;">Pratinjau Interaktif Diagram Arsitektur (Mermaid.js)</p>
                            <p style="font-size: 12px; color: #2563eb; margin: 0;">Menampilkan 7 diagram lengkap: UML Class, Sequence, State, ERD, DFD Level 0, DFD Level 1, dan Role Matrix.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ url('/diagrams/') }}" target="_blank" style="padding: 8px 16px; background: #2563eb; color: #ffffff; font-weight: 600; font-size: 12px; border-radius: 8px; text-decoration: none; display: inline-block;">
                            Buka Layar Penuh ↗
                        </a>
                    </div>
                </div>

                <!-- Embedded Interactive Diagram Iframe -->
                <div style="position: relative; width: 100%; height: 850px; border-radius: 18px; overflow: hidden; border: 1px solid #1e293b; box-shadow: 0 12px 40px rgba(0,0,0,0.3); background: #020817;">
                    <iframe 
                        src="{{ url('/diagrams/index.html') }}" 
                        style="width: 100%; height: 100%; border: none;"
                        title="Diagram Arsitektur Sistem Verifikasi BLUD"
                        loading="lazy"
                    ></iframe>
                </div>

                <!-- Diagram Quick Guide Cards -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 20px;">
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <span style="font-weight: 800; color: #2563eb; font-size: 13px;">01. UML Class</span>
                        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0;">Struktur model Eloquent, atribut dari migrasi database, dan relasi HasMany/BelongsTo.</p>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <span style="font-weight: 800; color: #9333ea; font-size: 13px;">02. Sequence Diagram</span>
                        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0;">Alur interaksi 5 fase dari PPTK submit hingga Pengarsipan digital oleh Admin.</p>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <span style="font-weight: 800; color: #ea580c; font-size: 13px;">03. State Diagram</span>
                        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0;">Transisi status dokumen: diajukan, diverifikasi, dikembalikan, disahkan, dibayar, diarsipkan.</p>
                    </div>

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <span style="font-weight: 800; color: #059669; font-size: 13px;">07. Role &amp; Permission</span>
                        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0;">Matriks hak akses 8 role pengguna terhadap Resource dan Widget Filament.</p>
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-filament-panels::page>
