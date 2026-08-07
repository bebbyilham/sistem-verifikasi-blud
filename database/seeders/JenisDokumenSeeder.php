<?php

namespace Database\Seeders;

use App\Models\JenisDokumen;
use Illuminate\Database\Seeder;

class JenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'kode_jenis' => 'SPP-LS',
                'nama_jenis' => 'SPP-LS (Langsung)',
                'deskripsi' => 'Surat Permintaan Pembayaran Langsung',
            ],
            [
                'kode_jenis' => 'SPP-UP',
                'nama_jenis' => 'SPP-UP (Uang Persediaan)',
                'deskripsi' => 'Surat Permintaan Pembayaran Uang Persediaan',
            ],
            [
                'kode_jenis' => 'SPP-GU',
                'nama_jenis' => 'SPP-GU (Ganti Uang)',
                'deskripsi' => 'Surat Permintaan Pembayaran Ganti Uang Persediaan',
            ],
            [
                'kode_jenis' => 'SPP-TU',
                'nama_jenis' => 'SPP-TU (Tambahan Uang)',
                'deskripsi' => 'Surat Permintaan Pembayaran Tambahan Uang Persediaan',
            ],
        ];

        foreach ($items as $item) {
            JenisDokumen::firstOrCreate(
                ['kode_jenis' => $item['kode_jenis']],
                $item
            );
        }
    }
}
