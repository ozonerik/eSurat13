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

        $deprecatedPermissions = [
            'master.jenis-surat.manage',
            'master.counter-surat.manage',
            'surat.review',
            'surat.approve-reject',
            'surat.archive.view',
            'audit-log.view',
            'telegram-log.view',
            'telegram-chat-id.manage',
            'jenis-surat.read',
            'jenis-surat.update',
            'jenis-surat.delete',
            'surat.draft.read',
            'surat.dikirim.read',
            'surat.disetujui.read',
            'surat.ditolak.read',
            'surat.expired.read',
            'surat.review.read',
            'surat.review.update',
        ];

        Permission::query()->whereIn('name', $deprecatedPermissions)->delete();

        $permissions = [
            'audit-log.read',
            'telegram-log.read',
            'sekolah.read',
            'sekolah.update',
            'kepala-sekolah.read',
            'kepala-sekolah.update',
            'kategori-surat.create',
            'kategori-surat.read',
            'kategori-surat.update',
            'kategori-surat.delete',
            'jenis-surat.create',
            'jenis-surat.read.all',
            'jenis-surat.read.own',
            'jenis-surat.update.all',
            'jenis-surat.update.own',
            'jenis-surat.delete.all',
            'jenis-surat.delete.own',
            'counter-surat.create',
            'counter-surat.read',
            'counter-surat.update',
            'counter-surat.delete',
            'surat.create',
            'surat.draft.read.all',
            'surat.draft.read.own',
            'surat.dikirim.read.all',
            'surat.dikirim.read.own',
            'surat.disetujui.read.all',
            'surat.disetujui.read.own',
            'surat.ditolak.read.all',
            'surat.ditolak.read.own',
            'surat.expired.read.all',
            'surat.expired.read.own',
            'surat.review.read.all',
            'surat.review.read.assigned',
            'surat.review.update.all',
            'surat.review.update.assigned',
            'surat.delete.null-number.own',
            'surat.delete.null-number.all',
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            'role.create',
            'role.read',
            'role.update',
            'role.delete',
            'permission.create',
            'permission.read',
            'permission.update',
            'permission.delete',
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

        $rolePermissionMap = [
            'Admin' => $permissions,
            'Kepala Sekolah' => [
                'sekolah.read',
                'sekolah.update',
                'kepala-sekolah.read',
                'kepala-sekolah.update',
                'surat.create',
                'surat.draft.read.own',
                'surat.dikirim.read.own',
                'surat.disetujui.read.all',
                'surat.ditolak.read.all',
                'surat.expired.read.all',
                'surat.review.read.assigned',
                'surat.review.update.assigned',
                'surat.delete.null-number.own',
            ],
            'Guru' => [
                'surat.create',
                'surat.draft.read.own',
                'surat.dikirim.read.own',
                'surat.disetujui.read.own',
                'surat.ditolak.read.own',
                'surat.expired.read.own',
                'surat.delete.null-number.own',
            ],
            'TU' => [
                'sekolah.read',
                'sekolah.update',
                'kepala-sekolah.read',
                'kepala-sekolah.update',
                'surat.create',
                'surat.draft.read.own',
                'surat.dikirim.read.own',
                'surat.disetujui.read.own',
                'surat.ditolak.read.own',
                'surat.expired.read.own',
                'surat.delete.null-number.own',
            ],
            'Kaprog' => [
                'jenis-surat.create',
                'jenis-surat.read.own',
                'jenis-surat.update.own',
                'jenis-surat.delete.own',
                'surat.create',
                'surat.draft.read.own',
                'surat.dikirim.read.own',
                'surat.disetujui.read.own',
                'surat.ditolak.read.own',
                'surat.expired.read.own',
                'surat.delete.null-number.own',
            ],
            'Wakil Kepala Sekolah' => [
                'jenis-surat.create',
                'jenis-surat.read.own',
                'jenis-surat.update.own',
                'jenis-surat.delete.own',
                'surat.create',
                'surat.draft.read.own',
                'surat.dikirim.read.own',
                'surat.disetujui.read.own',
                'surat.ditolak.read.own',
                'surat.expired.read.own',
                'surat.delete.null-number.own',
            ],
            'Pengelola Surat' => [
                'kategori-surat.create',
                'kategori-surat.read',
                'kategori-surat.update',
                'kategori-surat.delete',
                'jenis-surat.create',
                'jenis-surat.read.all',
                'jenis-surat.update.all',
                'jenis-surat.delete.all',
                'counter-surat.create',
                'counter-surat.read',
                'counter-surat.update',
                'counter-surat.delete',
                'surat.create',
                'surat.draft.read.all',
                'surat.dikirim.read.all',
                'surat.disetujui.read.all',
                'surat.ditolak.read.all',
                'surat.expired.read.all',
                'surat.review.read.all',
                'surat.delete.null-number.all',
            ],
        ];

        foreach ($rolePermissionMap as $roleName => $rolePermissions) {
            Role::findByName($roleName, 'web')->syncPermissions($rolePermissions);
        }

        Role::whereIn('name', ['Pembuat Surat', 'Pimpinan', 'Viewer Arsip'])->delete();

        $admin = User::firstOrCreate(
            ['email' => env('ESURAT_ADMIN_EMAIL', 'admin@test.id')],
            [
                'name' => env('ESURAT_ADMIN_NAME', 'Administrator eSurat'),
                'nip' => env('ESURAT_ADMIN_NIP',null),
                'password' => Hash::make(env('ESURAT_ADMIN_PASSWORD', 'admin')),
                'telegram_chat_id' => env('ESURAT_ADMIN_CHAT_ID'),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole($adminRole)) {
            $admin->assignRole($adminRole);
        }
    }
}
