<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $allowed_host = [
            'localhost',
            'stage.magiadeluco.com',
            'www.magiadeluco.com',
            'magiadeluco.com',
            'magiadeuco.com',
            'www.magiadeuco.com',
        ];

        if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_host))
        {
            if (isset($_SERVER['SERVER_PROTOCOL'])) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                exit;
            }
        }
    }
}
