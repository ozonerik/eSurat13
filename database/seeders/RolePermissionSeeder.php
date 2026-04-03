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
        $pembuatRole = Role::firstOrCreate(['name' => 'Pembuat Surat', 'guard_name' => 'web']);
        $approverRole = Role::firstOrCreate(['name' => 'Pimpinan', 'guard_name' => 'web']);
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer Arsip', 'guard_name' => 'web']);

        $adminRole->syncPermissions($permissions);
        $pembuatRole->syncPermissions([
            'surat.create',
            'surat.archive.view',
        ]);
        $approverRole->syncPermissions([
            'surat.review',
            'surat.approve-reject',
            'surat.archive.view',
        ]);
        $viewerRole->syncPermissions([
            'surat.archive.view',
        ]);

        $admin = User::firstOrCreate(
            ['email' => env('ESURAT_ADMIN_EMAIL', 'admin@esurat.local')],
            [
                'name' => env('ESURAT_ADMIN_NAME', 'Administrator eSurat'),
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
