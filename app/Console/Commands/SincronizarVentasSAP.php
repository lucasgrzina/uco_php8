<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Services\SAPService;

class SincronizarVentasSAP extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sincronizar-ventas-sap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proceso para sincronizar ventas con SAP';

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
        $this->sapService->sincronizarVentas();
    }
}
