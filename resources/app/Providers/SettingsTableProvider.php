<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Facades\Settings\Settings;

class SettingsTableProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('SettingsFacade',function(){
            return new Settings();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $settings = Setting::first();
        // View::share('settings',$settings);
    }
}
