<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('----------- Role -----------');

        // Gunakan firstOrCreate agar tidak ada duplikasi
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $this->command->info('----------- Assign Permissions to Role -----------');

        // Pemberian permission kepada admin
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $adminRole->givePermissionTo($permission->name);
        }

        $this->command->info('----------- User -----------');

        // Buat user dengan email unik
        $superadmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'password' => 'password'
        ]);
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'password' => 'password'
        ]);


        $this->command->info('----------- Assign Role to User -----------');

        // Assign role ke masing-masing user
        $superadmin->assignRole($superAdminRole);
        $admin->assignRole($adminRole);
    }
}
