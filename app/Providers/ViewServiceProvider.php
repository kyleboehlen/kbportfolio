<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Admin/Auth
        View::composer('admin.*', 'App\View\Composers\AdminComposer');
        View::composer('auth.*', 'App\View\Composers\AdminComposer');
    }
}
