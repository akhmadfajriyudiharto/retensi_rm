<?php

use App\Helpers\Menu;
use App\Http\Controllers\language\LanguageController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::middleware([
  'auth:web',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

    $menuFile = base_path('resources/menu/verticalMenu.json');

    // Validasi apakah file menu ada
    if (file_exists($menuFile)) {
        $verticalMenuJson = file_get_contents($menuFile);
        $verticalMenuData = json_decode($verticalMenuJson);

        if ($verticalMenuData && isset($verticalMenuData->menu) && is_array($verticalMenuData->menu)) {
            Menu::setRouteMenu($verticalMenuData->menu);
        } else {
            Log::error("Invalid or malformed JSON in menu file: $menuFile");
        }
    } else {
        Log::error("Menu file not found: $menuFile");
    }

    Route::get('/test', [\App\Http\Controllers\pages\HomePage::class, 'index']);
    Route::get('/pages/misc-error', ['App\Http\Controllers\pages\MiscError', 'index'])->name('pages-misc-error');
});
// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
