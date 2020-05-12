<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom styled button
        Blade::component('components.elements.b_button', 'b_button');
        // Custom styled link/anchor
        Blade::component('components.elements.l_button', 'l_button');
        
        // Customer page titles
        Blade::component('components.elements.page_title', 'page_title');
    }
}
