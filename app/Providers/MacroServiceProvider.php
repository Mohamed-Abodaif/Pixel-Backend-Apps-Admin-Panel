<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{



    /**
     * bootstrap any macro services.
     *
     * @return void
     */
    public function boot()
    {
        collect(glob(__DIR__ . '/../Macros/*.php'))
            ->each(function ($path) {
                require $path;
            });
    }

    /**
     * register any macro services.
     *
     * @return void
     */
    public function register()
    {
    }
}
