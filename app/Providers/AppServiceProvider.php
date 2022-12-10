<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use App\Models\Transaksi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('layouts.backend_master', function ($view) {
            $view->with('setting', Setting::first());
        });

        view()->composer('layouts.backend_master', function ($view) {
            $view->with('riwayat', Transaksi::orderBy('id','desc')->limit(10)->get());
        });

        view()->composer('auth.login', function ($view) {
            $view->with('setting', Setting::first());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
