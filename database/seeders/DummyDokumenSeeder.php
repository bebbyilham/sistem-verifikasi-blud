<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\DokumenPengeluaran;
use App\Models\JenisKesalahan;
use App\Models\Pembayaran;
use App\Models\Pengesahan;
use App\Models\RiwayatKoreksi;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Database\Seeder;

class DummyDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $pptkMedis = User::where('email', 'pptk.medis@blud.com')->first();
        $pptkUmum = User::where('email', 'pptk.umum@blud.com')->first();
        $pptkPenunjang = User::where('email', 'pptk.penunjang@blud.com')->first();
        $verifikator = User::where('email', 'verifikator1@blud.com')->first();
        $ppk = User::where('email', 'ppk@blud.com')->first();
        $bendahara = User::where('email', 'bendahara@blud.com')->first();
        $jenisKesalahan = JenisKesalahan::first();

        if (!$pptkMedis || !$verifikator || !$ppk || !$bendahara) {
            return;
        }

        // 1. Dokumen Diajukan (Menunggu Verifikasi)
        DokumenPengeluaran::create([
            'kode_dokumen' => 'BLUD-2026-08-0001',
            'bidang_id' => $pptkMedis->bidang_id,
            'pptk_id' => $pptkMedis->id,
            'jenis_dokumen' => 'SPP-LS',
            'sumber_dana' => 'BLUD',
            'nominal' => 12500000.00,
            'tanggal_ajuan' => now()->subDays(5)->toDateString(),
            'status' => DokumenPengeluaran::STATUS_DIAJUKAN,
            'file_path' => [
                ['judul' => 'Kwitansi Tagihan Obat', 'file' => 'dokumen_pengeluaran/sample_kwitansi.pdf'],
                ['judul' => 'Surat Pesanan Farmasi', 'file' => 'dokumen_pengeluaran/sample_sp.pdf'],
            ],
            'keterangan' => 'Pengadaan obat-obatan RSJ triwulan III',
        ]);

        // 2. Dokumen Dikembalikan (Revisi) dengan Riwayat Koreksi
        $dok2 = DokumenPengeluaran::create([
            'kode_dokumen' => 'BLUD-2026-08-0002',
            'bidang_id' => $pptkUmum?->bidang_id ?? $pptkMedis->bidang_id,
            'pptk_id' => $pptkUmum?->id ?? $pptkMedis->id,
            'jenis_dokumen' => 'SPP-GU',
            'sumber_dana' => 'BLUD',
            'nominal' => 4750000.00,
            'tanggal_ajuan' => now()->subDays(4)->toDateString(),
            'status' => DokumenPengeluaran::STATUS_DIKEMBALIKAN,
            'file_path' => [
                ['judul' => 'Nota Pembelian Alat Tulis', 'file' => 'dokumen_pengeluaran/sample_nota.pdf'],
            ],
            'keterangan' => 'Pembelian ATK Subbag Umum',
        ]);

        Verifikasi::create([
            'dokumen_id' => $dok2->id,
            'verifikator_id' => $verifikator->id,
            'hasil' => 'dikembalikan',
            'tanggal_verifikasi' => now()->subDays(3),
            'catatan' => 'Mohon lengkapi tanda tangan PPTK pada nota fisik.',
        ]);

        RiwayatKoreksi::create([
            'dokumen_id' => $dok2->id,
            'versi_ke' => 1,
            'jenis_kesalahan_id' => $jenisKesalahan?->id,
            'catatan_koreksi' => 'Mohon lengkapi tanda tangan PPTK pada nota fisik.',
            'tanggal_koreksi' => now()->subDays(3),
            'dikoreksi_oleh' => $verifikator->id,
        ]);

        // 3. Dokumen Diverifikasi (Menunggu PPK)
        $dok3 = DokumenPengeluaran::create([
            'kode_dokumen' => 'BLUD-2026-08-0003',
            'bidang_id' => $pptkPenunjang?->bidang_id ?? $pptkMedis->bidang_id,
            'pptk_id' => $pptkPenunjang?->id ?? $pptkMedis->id,
            'jenis_dokumen' => 'SPP-LS',
            'sumber_dana' => 'BLUD',
            'nominal' => 28400000.00,
            'tanggal_ajuan' => now()->subDays(3)->toDateString(),
            'status' => DokumenPengeluaran::STATUS_DIVERIFIKASI,
            'file_path' => [
                ['judul' => 'SPK Pemeliharaan AC', 'file' => 'dokumen_pengeluaran/sample_spk.pdf'],
                ['judul' => 'BAST Pekerjaan', 'file' => 'dokumen_pengeluaran/sample_bast.pdf'],
            ],
            'keterangan' => 'Pemeliharaan berkala AC Central gedung utama',
        ]);

        Verifikasi::create([
            'dokumen_id' => $dok3->id,
            'verifikator_id' => $verifikator->id,
            'hasil' => 'lolos',
            'tanggal_verifikasi' => now()->subDays(2),
            'catatan' => 'Dokumen lengkap dan benar.',
        ]);

        // 4. Dokumen Disahkan (Menunggu Pembayaran)
        $dok4 = DokumenPengeluaran::create([
            'kode_dokumen' => 'BLUD-2026-08-0004',
            'bidang_id' => $pptkMedis->bidang_id,
            'pptk_id' => $pptkMedis->id,
            'jenis_dokumen' => 'SPP-UP',
            'sumber_dana' => 'BLUD',
            'nominal' => 15000000.00,
            'tanggal_ajuan' => now()->subDays(2)->toDateString(),
            'status' => DokumenPengeluaran::STATUS_DISAHKAN,
            'file_path' => [
                ['judul' => 'Surat Pengajuan Uang Persediaan', 'file' => 'dokumen_pengeluaran/sample_up.pdf'],
            ],
            'keterangan' => 'Pengajuan Uang Persediaan (UP) bidang Medis',
        ]);

        Verifikasi::create([
            'dokumen_id' => $dok4->id,
            'verifikator_id' => $verifikator->id,
            'hasil' => 'lolos',
            'tanggal_verifikasi' => now()->subDays(2),
            'catatan' => 'Verifikasi UP lolos.',
        ]);

        Pengesahan::create([
            'dokumen_id' => $dok4->id,
            'ppk_id' => $ppk->id,
            'tanggal_sah' => now()->subDay(),
            'catatan' => 'Disetujui untuk dicairkan oleh Bendahara.',
        ]);

        // 5. Dokumen Dibayar (Menunggu Diarsipkan)
        $dok5 = DokumenPengeluaran::create([
            'kode_dokumen' => 'BLUD-2026-08-0005',
            'bidang_id' => $pptkMedis->bidang_id,
            'pptk_id' => $pptkMedis->id,
            'jenis_dokumen' => 'SPP-LS',
            'sumber_dana' => 'BLUD',
            'nominal' => 52000000.00,
            'tanggal_ajuan' => now()->subDays(6)->toDateString(),
            'status' => DokumenPengeluaran::STATUS_DIBAYAR,
            'file_path' => [
                ['judul' => 'SPJ Pengadaan Alkes', 'file' => 'dokumen_pengeluaran/sample_alkes.pdf'],
            ],
            'keterangan' => 'Pembayaran tagihan pengadaan alat kesehatan',
        ]);

        Verifikasi::create([
            'dokumen_id' => $dok5->id,
            'verifikator_id' => $verifikator->id,
            'hasil' => 'lolos',
            'tanggal_verifikasi' => now()->subDays(5),
            'catatan' => 'Lolos verifikasi.',
        ]);

        Pengesahan::create([
            'dokumen_id' => $dok5->id,
            'ppk_id' => $ppk->id,
            'tanggal_sah' => now()->subDays(4),
            'catatan' => 'Disahkan PPK.',
        ]);

        Pembayaran::create([
            'dokumen_id' => $dok5->id,
            'bendahara_id' => $bendahara->id,
            'tanggal_bayar' => now()->subDays(2),
            'nomor_spj' => 'SPJ-2026/08/005',
            'status_bayar' => 'Lunas',
        ]);

        // 6. Dokumen Diarsipkan
        $dok6 = DokumenPengeluaran::create([
            'kode_dokumen' => 'BLUD-2026-08-0006',
            'bidang_id' => $pptkPenunjang?->bidang_id ?? $pptkMedis->bidang_id,
            'pptk_id' => $pptkPenunjang?->id ?? $pptkMedis->id,
            'jenis_dokumen' => 'SPP-TU',
            'sumber_dana' => 'BLUD',
            'nominal' => 8500000.00,
            'tanggal_ajuan' => now()->subDays(10)->toDateString(),
            'status' => DokumenPengeluaran::STATUS_DIARSIPKAN,
            'file_path' => [
                ['judul' => 'Dokumen Tambahan Uang', 'file' => 'dokumen_pengeluaran/sample_tu.pdf'],
            ],
            'keterangan' => 'Pengajuan Tambahan Uang (TU) operasional penunjang',
        ]);

        Verifikasi::create([
            'dokumen_id' => $dok6->id,
            'verifikator_id' => $verifikator->id,
            'hasil' => 'lolos',
            'tanggal_verifikasi' => now()->subDays(9),
            'catatan' => 'Lolos verifikasi.',
        ]);

        Pengesahan::create([
            'dokumen_id' => $dok6->id,
            'ppk_id' => $ppk->id,
            'tanggal_sah' => now()->subDays(8),
            'catatan' => 'Disahkan PPK.',
        ]);

        Pembayaran::create([
            'dokumen_id' => $dok6->id,
            'bendahara_id' => $bendahara->id,
            'tanggal_bayar' => now()->subDays(7),
            'nomor_spj' => 'SPJ-2026/08/006',
            'status_bayar' => 'Lunas',
        ]);
    }
}
