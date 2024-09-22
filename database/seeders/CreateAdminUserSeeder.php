<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('1234567890'),
            'created_by' => 1,
            'created_at' => Carbon::now(),
        ]);

        // Create the admin role
        $role = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
            'created_by' => 1,
            'created_at' => Carbon::now(),
        ]);

        // Assign the role to the user
        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
