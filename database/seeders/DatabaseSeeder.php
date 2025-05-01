<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $_ENV['SEEDING'] = true;

        $this->call(PermissionMenuStandardSeeder::class);
        $this->call(UserRoleStandardSeeder::class);
    }
}
