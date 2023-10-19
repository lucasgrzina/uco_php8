<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Services\SAPService;

class SincronizarSAP extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sincronizar-sap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso para sincronizar productos con SAP';

    protected $sapService = null;
    protected $obraSocialRepo = null;
    protected $institucionRepo = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SAPService $sapService)
    {
        parent::__construct();
        $this->sapService = $sapService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::channel('consola')->info("INICIO: cron:sincronizar-sap");
        $this->sapService->sincronizarProductos();
        \Log::channel('consola')->info("FIN: cron:sincronizar-sap");
    }
}
