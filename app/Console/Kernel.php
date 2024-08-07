<?php

namespace App\Console;

use App\Console\Commands\SincronizarSAP;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SincronizarVentasSAP;
use App\Console\Commands\ControlPedidosSinConfirmarMP;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SincronizarSAP::class,
        SincronizarVentasSAP::class,
        ControlPedidosSinConfirmarMP::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(SincronizarSAP::class)->everyTenMinutes()->withoutOverlapping();
        $schedule->command(SincronizarVentasSAP::class)->everyFiveMinutes()->withoutOverlapping();
        $schedule->command(ControlPedidosSinConfirmarMP::class)->everyFiveMinutes()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
