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
        // ====================================
        // 1. Seed Roles & Permissions (8 roles + Filament Shield permissions)
        // ====================================
        $roleNames = ['super_admin', 'admin', 'pptk', 'verifikator', 'ppk', 'bendahara', 'manajemen', 'rekanan'];
        $roles = [];
        foreach ($roleNames as $roleName) {
            $roles[$roleName] = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Generate Shield Permissions
        $resources = ['DokumenPengeluaran', 'User', 'Bidang', 'JenisKesalahan', 'JenisDokumen', 'AuditTrail', 'Role'];
        $prefixes = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny', 'Restore', 'RestoreAny', 'ForceDelete', 'ForceDeleteAny', 'Reorder', 'Replicate'];

        foreach ($resources as $resource) {
            foreach ($prefixes as $prefix) {
                \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => "{$prefix}:{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        $widgets = ['DokumenChart', 'PengeluaranChart', 'KoreksiPerBidangChart', 'WaktuVerifikasiChart', 'StatsOverviewWidget'];
        foreach ($widgets as $widget) {
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => "View:{$widget}",
                'guard_name' => 'web',
            ]);
        }

        $allPermissions = \Spatie\Permission\Models\Permission::all();

        // Assign Permissions
        $roles['super_admin']?->syncPermissions($allPermissions);
        $roles['admin']?->syncPermissions($allPermissions);

        $roles['pptk']?->syncPermissions([
            'ViewAny:DokumenPengeluaran', 'View:DokumenPengeluaran', 'Create:DokumenPengeluaran', 'Update:DokumenPengeluaran',
            'View:StatsOverviewWidget', 'View:DokumenChart'
        ]);

        $roles['verifikator']?->syncPermissions([
            'ViewAny:DokumenPengeluaran', 'View:DokumenPengeluaran', 'Update:DokumenPengeluaran',
            'View:StatsOverviewWidget', 'View:DokumenChart', 'View:WaktuVerifikasiChart'
        ]);

        $roles['ppk']?->syncPermissions([
            'ViewAny:DokumenPengeluaran', 'View:DokumenPengeluaran', 'Update:DokumenPengeluaran',
            'View:StatsOverviewWidget'
        ]);

        $roles['bendahara']?->syncPermissions([
            'ViewAny:DokumenPengeluaran', 'View:DokumenPengeluaran', 'Update:DokumenPengeluaran',
            'View:StatsOverviewWidget', 'View:PengeluaranChart'
        ]);

        $roles['manajemen']?->syncPermissions([
            'ViewAny:DokumenPengeluaran', 'View:DokumenPengeluaran', 'ViewAny:AuditTrail', 'View:AuditTrail',
            'View:StatsOverviewWidget', 'View:DokumenChart', 'View:PengeluaranChart', 'View:KoreksiPerBidangChart', 'View:WaktuVerifikasiChart'
        ]);

        $roles['rekanan']?->syncPermissions([
            'ViewAny:DokumenPengeluaran', 'View:DokumenPengeluaran'
        ]);

        // ====================================
        // 2. Seed 6 Bidang sesuai SK Direktur No. 900.1.15/004/RSJ-2026
        // ====================================
        $bidangData = [
            ['nama_bidang' => 'Penunjang', 'pptk_penanggung_jawab' => 'PPTK Penunjang'],
            ['nama_bidang' => 'Umum', 'pptk_penanggung_jawab' => 'PPTK Umum'],
            ['nama_bidang' => 'SDM dan Litbang', 'pptk_penanggung_jawab' => 'PPTK SDM Litbang'],
            ['nama_bidang' => 'Pelayanan Medis', 'pptk_penanggung_jawab' => 'PPTK Pelayanan Medis'],
            ['nama_bidang' => 'Keperawatan', 'pptk_penanggung_jawab' => 'PPTK Keperawatan'],
            ['nama_bidang' => 'Keuangan', 'pptk_penanggung_jawab' => 'PPTK Keuangan'],
        ];

        $bidangs = [];
        foreach ($bidangData as $data) {
            $bidangs[$data['nama_bidang']] = Bidang::firstOrCreate(
                ['nama_bidang' => $data['nama_bidang']],
                $data
            );
        }

        // ====================================
        // 3. Seed 5 Jenis Kesalahan (baseline)
        // ====================================
        $jenisKesalahanData = [
            ['nama_kesalahan' => 'Dokumen tidak lengkap', 'deskripsi' => 'Dokumen yang dilampirkan tidak memenuhi persyaratan kelengkapan'],
            ['nama_kesalahan' => 'Format tidak sesuai', 'deskripsi' => 'Format dokumen tidak sesuai dengan standar yang ditentukan'],
            ['nama_kesalahan' => 'Tanda tangan/pengesahan kurang', 'deskripsi' => 'Dokumen belum ditandatangani oleh pihak yang berwenang'],
            ['nama_kesalahan' => 'Nominal tidak sesuai', 'deskripsi' => 'Nominal yang tertera tidak sesuai dengan bukti pendukung'],
            ['nama_kesalahan' => 'Lainnya', 'deskripsi' => 'Kesalahan lain yang tidak termasuk dalam kategori di atas'],
        ];

        foreach ($jenisKesalahanData as $data) {
            JenisKesalahan::firstOrCreate(
                ['nama_kesalahan' => $data['nama_kesalahan']],
                $data
            );
        }

        // ====================================
        // 4. Seed Jenis Dokumen
        // ====================================
        $this->call(JenisDokumenSeeder::class);

        // ====================================
        // 5. Seed Users
        // ====================================
        $defaultPassword = Hash::make('password');

        $usersData = [
            // System roles
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

            // PPTK — satu per bidang
            [
                'name' => 'PPTK Penunjang',
                'email' => 'pptk.penunjang@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangs['Penunjang']->id
            ],
            [
                'name' => 'PPTK Umum',
                'email' => 'pptk.umum@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangs['Umum']->id
            ],
            [
                'name' => 'PPTK SDM Litbang',
                'email' => 'pptk.sdm@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangs['SDM dan Litbang']->id
            ],
            [
                'name' => 'PPTK Pelayanan Medis',
                'email' => 'pptk.medis@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangs['Pelayanan Medis']->id
            ],
            [
                'name' => 'PPTK Keperawatan',
                'email' => 'pptk.keperawatan@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangs['Keperawatan']->id
            ],
            [
                'name' => 'PPTK Keuangan',
                'email' => 'pptk.keuangan@blud.com',
                'role' => 'pptk',
                'bidang_id' => $bidangs['Keuangan']->id
            ],

            // Functional roles
            [
                'name' => 'Verifikator 1',
                'email' => 'verifikator1@blud.com',
                'role' => 'verifikator',
                'bidang_id' => null
            ],
            [
                'name' => 'PPK BLUD',
                'email' => 'ppk@blud.com',
                'role' => 'ppk',
                'bidang_id' => null
            ],
            [
                'name' => 'Bendahara Pengeluaran',
                'email' => 'bendahara@blud.com',
                'role' => 'bendahara',
                'bidang_id' => null
            ],
            [
                'name' => 'Manajemen RS',
                'email' => 'manajemen@blud.com',
                'role' => 'manajemen',
                'bidang_id' => null
            ],
            [
                'name' => 'Rekanan Demo',
                'email' => 'rekanan@blud.com',
                'role' => 'rekanan',
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

        // Seed Dummy Documents
        $this->call(DummyDokumenSeeder::class);
    }
}
