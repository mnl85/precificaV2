<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\tb_empresa;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tb_empresa', function ($app) {
            return new tb_empresa;
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
