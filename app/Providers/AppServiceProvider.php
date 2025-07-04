<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // Berikan semua izin kepada pengguna dengan peran Super Admin
    Gate::before(function ($user, $ability) {
        // Daftar ability yang ingin dibatasi
        $blockedAbilities = [
            'admin.retensi.create',
            'admin.retensi.delete',
            'admin.pemusnahan.create',
            'admin.pemusnahan.delete',
            'admin.laporan-rekam-medis.create',
            'admin.laporan-rekam-medis.action',
        ];

        // Tolak jika termasuk dalam blocked
        if (in_array($ability, $blockedAbilities)) {
            return false;
        }
        return $user->hasRole('Super Admin') ? true : null;
    });
    Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
      if ($src !== null) {
        return [
          'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' :
                    (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
        ];
      }
      return [];
    });
  }
}
