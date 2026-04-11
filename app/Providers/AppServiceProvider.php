<?php

namespace App\Providers;

use App\Models\AppSetting;
use App\Models\WebSetting;
use App\Repositories\WebSettingRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

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
        if (Schema::hasTable('web_settings')) {
            $webSettings =(new WebSettingRepository())->getAll();
            foreach ($webSettings as $setting) {
                $decoded = json_decode($setting->value);
                $setting->decoded_value = $decoded;
                $setting->key = $setting->key ?? null;
            }
            View::share('webSettings', $webSettings);
        } else {
            View::share('webSettings', []);
        }

        if (Schema::hasTable('app_settings')) {
            $appSetting = AppSetting::first();
            $currency = $appSetting?->currency ?? '$';
            View::share('appSetting', $appSetting);
            View::share('currency', $currency);
        } else {
            View::share('currency', []);
        }

    }
}
