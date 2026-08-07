<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bidang;
use App\Models\JenisKesalahan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles
        $roles = ['super_admin', 'admin', 'pptk', 'verifikator', 'ppk', 'bendahara', 'manajemen'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Seed Bidang
        $bidangTIK = Bidang::firstOrCreate([
            'nama_bidang' => 'Teknologi Informasi dan Komunikasi',
            'pptk_penanggung_jawab' => 'Budi TIK'
        ]);
        
        $bidangMedis = Bidang::firstOrCreate([
            'nama_bidang' => 'Pelayanan Medis',
            'pptk_penanggung_jawab' => 'Siti Medis'
        ]);

        // Seed Jenis Kesalahan
        JenisKesalahan::firstOrCreate([
            'nama_kesalahan' => 'Salah Nominal',
            'deskripsi' => 'Nominal di kuitansi tidak sesuai dengan nota'
        ]);
        JenisKesalahan::firstOrCreate([
            'nama_kesalahan' => 'Tanda Tangan Tidak Lengkap',
            'deskripsi' => 'Dokumen belum ditandatangani oleh pihak terkait'
        ]);
        JenisKesalahan::firstOrCreate([
            'nama_kesalahan' => 'Bukti Dukung Kurang',
            'deskripsi' => 'Lampiran bukti pendukung tidak lengkap'
        ]);

        // Seed Jenis Dokumen
        $this->call(JenisDokumenSeeder::class);

        // Seed Users
        $defaultPassword = Hash::make('password');

        $usersData = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@blud.com',
                'role' => 'super_admin',
                'bidang_id' => null
            ],
            [
                'name' => 'Admin Keuangan',
                'email' => 'admin@blud.com',
                'role' => 'admin',
                'bidang_id' => null
            ],
            [
                'name' => 'PPTK TIK',
                'email' => 'pptk.tik@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangTIK->id
            ],
            [
                'name' => 'PPTK Medis',
                'email' => 'pptk.medis@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangMedis->id
            ],
            [
                'name' => 'Verifikator 1',
                'email' => 'verifikator1@blud.com',
                'role' => 'verifikator',
                'bidang_id' => null
            ],
            [
                'name' => 'PPK',
                'email' => 'ppk@blud.com',
                'role' => 'ppk',
                'bidang_id' => null
            ],
            [
                'name' => 'Bendahara',
                'email' => 'bendahara@blud.com',
                'role' => 'bendahara',
                'bidang_id' => null
            ],
            [
                'name' => 'Manajemen',
                'email' => 'manajemen@blud.com',
                'role' => 'manajemen',
                'bidang_id' => null
            ],
        ];

        foreach ($usersData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $defaultPassword,
                    'bidang_id' => $data['bidang_id'],
                    'status_aktif' => true,
                ]
            );

            if (!$user->hasRole($data['role'])) {
                $user->assignRole($data['role']);
            }
        }
    }
}
