<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class Menu
{
    /**
     * Rekursif untuk membuat route berdasarkan struktur menu.
     *
     * @param array $menus
     */
    public static function setRouteMenu($menus)
    {
        foreach ($menus as $menu) {
            if (isset($menu->submenu) && is_array($menu->submenu)) {
                self::setRouteMenu($menu->submenu);
            } else {
                if (isset($menu->url, $menu->controller, $menu->slug) && is_array($menu->controller)) {
                    if (count($menu->controller) === 2) {
                        Route::get($menu->url, [$menu->controller[0], $menu->controller[1]])
                            ->name($menu->slug)->middleware(['can:' . $menu->slug . '.view']);
                    } else {
                        Log::error("Invalid controller format in menu: ", (array) $menu);
                    }
                } else {
                    Log::error("Invalid menu structure: ", (array) $menu);
                }
            }
        }
    }
}
