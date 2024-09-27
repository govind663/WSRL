<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
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
            'department_id' => '1',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('1234567890'),
            'created_by' => 1,
            'created_at' => Carbon::now(),
        ]);

        // Create the Admin role if it doesn't exist
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ], [
            'created_by' => 1,
            'created_at' => Carbon::now(),
        ]);

        // Create the User role if it doesn't exist
        $userRole = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
        ], [
            'created_by' => 1,
            'created_at' => Carbon::now(),
        ]);

        // Get all permissions
        $permissions = Permission::pluck('id', 'id')->all();

        // Sync all permissions to the Admin role
        $adminRole->syncPermissions($permissions);

        // Assign the Admin role to the admin user
        $user->assignRole($adminRole);

        // Optionally, you can create a normal user with the User role (example)
        $normalUser = User::create([
            'name' => 'Abhishek G Jha',
            'email' => 'codingthunder1997@gmail.com',
            'department_id' => '2',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('1234567890'),
            'created_by' => 1,
            'created_at' => Carbon::now(),
        ]);

        // Assign the User role to the normal user
        $normalUser->assignRole($userRole);
    }
}
