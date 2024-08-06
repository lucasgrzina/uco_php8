<?php

namespace App\Console\Commands;

use App\Pedido;

use GuzzleHttp\Client;

use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;
use App\Console\Commands\BaseCommand;

class ControlPedidosSinConfirmarMP extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:control-pedidos-sin-confirmar-mp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = -2;

        $now = \Carbon\Carbon::now();

        $resp = Pedido::whereNotNull('pp_preference_id')/*->where('pp_status','<>', 'aprobado')*/
                ->where(function ($q) {
                    $q->where('pp_status', 'pendiente')->orWhereNull('pp_status');
                })
                ->where('estado_id',0)
                ->where('created_at','>', $now->addDays($days)->toDateTimeString())
                ->get()
        ;
        foreach($resp as $pedido)
        {
            try {
                if ($pedido->tipo_factura == 'CF') {
                    $pedido = repo('Pedido')->actualizarPago($pedido,true);
                    if ($pedido->pp_status == 'aprobado') {
                        repo('Pedido')->notificarNuevoPedido($pedido);
                    }
                }

            } catch (\Exception $e) {
                \Log::error('cron:control-pedidos-sin-confirmar-mp: '.$e->getMessage());
            }
        }

    }
}
