<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionMenuStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
        if ($verticalMenuJson === false) {
            $this->command->error('Failed to read verticalMenu.json file.');
            return;
        }

        $verticalMenuData = json_decode($verticalMenuJson);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Error decoding JSON: ' . json_last_error_msg());
            return;
        }

        $this->command->info('-----------Permission-----------');
        $this->setPermission($verticalMenuData->menu);
    }

    private function setPermission($menus)
    {
        foreach ($menus as $key => $menu) {
            if (isset($menu->submenu))
                $this->setPermission($menu->submenu);
            else{
                if (!isset($menu->slug) || empty($menu->slug))
                    continue;

                $this->command->info("Menambahkan permissions: {$menu->slug}");

                $permissions = array_merge($menu->permissions ?? [], [
                    $menu->slug . '.view',
                    $menu->slug . '.create',
                    $menu->slug . '.update',
                    $menu->slug . '.delete',
                ]);

                foreach ($permissions as $permission) {
                    Permission::firstOrCreate(['name' => $permission]);
                    $this->command->info("Permission: {$permission}");
                }
            }
        }
    }
}
