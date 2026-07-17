<?php

namespace App\Providers;

use App\Services\AppSettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Bagikan pengaturan app (nama & logo) ke layout utama & guest,
        // supaya begitu Admin ganti di halaman Pengaturan, langsung
        // kepakai di navbar/favicon tanpa perlu ubah tiap view satu-satu.
        View::composer(['layouts.app', 'layouts.guest'], function ($view) {
            try {
                $view->with('appSetting', app(AppSettingService::class)->current());
            } catch (\Throwable $e) {
                // Migration belum jalan (mis. pas fresh install) -> fallback ke null,
                // view sudah dijaga null-safe (lihat helper di bawah).
                $view->with('appSetting', null);
            }
        });
    }
}
