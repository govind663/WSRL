<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // role
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            // permission
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            // user
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            // qrcode
            'qrcode-list',
            'qrcode-create',
            'qrcode-edit',
            'qrcode-delete',
            // product
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            // distributor
            'distributor-list',
            'distributor-create',
            'distributor-edit',
            'distributor-delete',
            // dispatch
            'dispatch-list',
            'dispatch-create',
            'dispatch-edit',
            'dispatch-delete',
            // report
            'distributor-report-list',
            'doctor-report-list',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
                'created_by' => 1,
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
