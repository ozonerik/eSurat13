<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'master.jenis-surat.manage',
            'master.counter-surat.manage',
            'surat.create',
            'surat.review',
            'surat.approve-reject',
            'surat.archive.view',
            'audit-log.view',
            'telegram-log.view',
            'telegram-chat-id.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $kepalaSekolahRole = Role::firstOrCreate(['name' => 'Kepala Sekolah', 'guard_name' => 'web']);
        $guruRole = Role::firstOrCreate(['name' => 'Guru', 'guard_name' => 'web']);
        $tuRole = Role::firstOrCreate(['name' => 'TU', 'guard_name' => 'web']);
        $kaprogRole = Role::firstOrCreate(['name' => 'Kaprog', 'guard_name' => 'web']);
        $wakasekRole = Role::firstOrCreate(['name' => 'Wakil Kepala Sekolah', 'guard_name' => 'web']);
        $pengelolaSuratRole = Role::firstOrCreate(['name' => 'Pengelola Surat', 'guard_name' => 'web']);

        $adminRole->syncPermissions($permissions);
        $guruRole->syncPermissions([
            'surat.create',
            'surat.archive.view',
        ]);
        $kepalaSekolahRole->syncPermissions([
            'surat.review',
            'surat.approve-reject',
            'surat.archive.view',
        ]);
        $wakasekRole->syncPermissions([
            'surat.review',
            'surat.approve-reject',
            'surat.archive.view',
        ]);
        $kaprogRole->syncPermissions([
            'surat.create',
            'surat.review',
            'surat.archive.view',
        ]);
        $tuRole->syncPermissions([
            'master.jenis-surat.manage',
            'master.counter-surat.manage',
            'telegram-chat-id.manage',
            'surat.archive.view',
        ]);
        $pengelolaSuratRole->syncPermissions([
            'surat.create',
            'surat.review',
            'surat.archive.view',
        ]);

        Role::whereIn('name', ['Pembuat Surat', 'Pimpinan', 'Viewer Arsip'])->delete();

        $admin = User::firstOrCreate(
            ['email' => env('ESURAT_ADMIN_EMAIL', 'admin@esurat.local')],
            [
                'name' => env('ESURAT_ADMIN_NAME', 'Administrator eSurat'),
                'nip' => env('ESURAT_ADMIN_NIP', '000000000000000001'),
                'password' => Hash::make(env('ESURAT_ADMIN_PASSWORD', 'password')),
                'telegram_chat_id' => env('ESURAT_ADMIN_CHAT_ID'),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole($adminRole)) {
            $admin->assignRole($adminRole);
        }
    }
}
