<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //register
        require_once app_path() . '/Helpers/FileUpload.php';
        require_once app_path() . '/Helpers/AdminHelper.php';
        require_once app_path() . '/Helpers/FrontHelper.php';
        require_once app_path() . '/Helpers/general.php';
    }
}
