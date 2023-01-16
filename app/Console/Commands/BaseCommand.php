<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\Notifiable;

class BaseCommand extends Command
{
    use Notifiable;

    protected $horaInicioEjecucion;
    protected $horaFinEjecucion;
    protected $emailSalida = null;
    protected $ex = null;
    protected $datosSalida = [];
    protected $signature = 'cron:base';


    public function __construct()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        parent::__construct();

    }

}
