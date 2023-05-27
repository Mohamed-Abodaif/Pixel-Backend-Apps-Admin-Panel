<?php

namespace App\Providers;

use App\Files\Excel\FastExcel;
use App\Interfaces\ExcelInterface;
use Illuminate\Support\ServiceProvider;

class InjectProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExcelInterface::class, FastExcel::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
