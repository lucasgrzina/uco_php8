<?php

namespace App\Repositories;

use App\Pedido;
use App\Configuraciones;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PedidoRepository
 * @package App\Repositories
 * @version November 22, 2022, 10:03 pm -03
 *
 * @method Pedido findWithoutFail($id, $columns = ['*'])
 * @method Pedido find($id, $columns = ['*'])
 * @method Pedido first($columns = ['*'])
*/
class PedidoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Pedido::class;
    }

    public function altaDesdeCarrito($data, $items)
    {
        try {
            \DB::beginTransaction();
            $modelo = $this->model->newQuery()->create($data);
            $modelo->items()->createMany($items);
            \DB::commit();
            return $modelo;
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage(), 1);
        }
    }

    public function generarEnvio($model)
    {
        try {
            $model->load(['items.aniada.vino']);

            $productos = [];
            foreach ($model->items as $item) {
                for($x=0; $x < $item->cantidad; $x++) {
                    array_push($productos, $item);
                }
            }

            $respuesta = servicio('UPS')->generarEnvio($model->id
                ,$model->items
                ,$model->direccion
                ,$model->ciudad
                ,$model->pais->codigo
                ,$model->cp
                ,$model->nombre
                ,$model->apellido
                ,$model->email
                ,''
            );

            $model->ups_tracking_number = $respuesta['tracking_number'];
            $model->ups_etiqueta = $respuesta['etiqueta'];
            $model->ups_info = \Arr::except($respuesta,['tracking_number','etiqueta']);
            $model->save();

            return true;
        } catch (\Exception $ex) {
            logInfo('CheckoutController::generarEnvio => '.$ex->getMessage());
            return false;
            //return $this->sendError($ex->getMessage(),500);
        }

    }

    public function actualizarPago($pedido) {
        $mpPago = servicio('MP')->buscarPagoPorPedidoId($pedido->id);
        //dd($mpPago);
        if($mpPago) {
            $pedido->pp_payment_type = $mpPago->payment_type_id;
            if ($mpPago->payment_method) {
                $pedido->tipo_tarjeta = $mpPago->payment_method->id;
            }
            if (isset($mpPago->card) && $mpPago->card) {
                $pedido->tarjeta_cuotas = $mpPago->installments;
                $pedido->tarjeta = isset($mpPago->card->last_four_digits) ? $mpPago->card->last_four_digits : null;
                $pedido->tarjeta_exp = isset($mpPago->card->expiration_month) ? $mpPago->card->expiration_month.'/'.$mpPago->card->expiration_year : null;
            }

            if ($mpPago->status == 'approved'){
                $pedido->numero_voucher = $mpPago->id;
                $pedido->pp_status = 'aprobado';
                $pedido->estado_id = 1;

                if (!$pedido->ups_tracking_number) {
                    $this->generarEnvio($pedido);
                }
            }else{
                switch($mpPago->status) {
                    case 'pending':
                        $pedido->pp_status = 'pendiente';
                        break;
                    case 'rejected':
                        $pedido->pp_status = 'rechazado';
                        $pedido->estado_id = -1;
                        break;
                    case 'cancelled':
                        $pedido->pp_status = 'cancelado';
                        $pedido->estado_id = -1;
                        break;
                }
            }
            $pedido->pp_status_desc = $mpPago->status_detail;
            $pedido->save();

        }
        return $pedido;
    }

    public function notificarNuevoPedido($pedido) {
        $configuraciones = Configuraciones::whereIn('clave',['AVISO_NUEVOS_PEDIDOS'])->pluck('valor','clave')->toArray();

        if(isset($configuraciones['AVISO_NUEVOS_PEDIDOS']) && $configuraciones['AVISO_NUEVOS_PEDIDOS']) {
            try
            {
                logger($configuraciones);
                $url = route('pedidos.edit',[$pedido->id]);
                logger($url);
                $html = "
                    Un nuevo pedido ha ingresado en la tienda.<br>Para conocer más sobre el mismo, haga click <a href='{$url}'>aquí</a>
                ";
                logger($html);
                \Mail::send([], [], function (Message $message) use ($html,$configuraciones) {
                    $message->to($configuraciones['AVISO_NUEVOS_PEDIDOS'])
                    ->subject('Nuevo pedido en tienda')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->setBody($html, 'text/html');
                });

            }
            catch(\Exception $ex)
            {
                logger($ex->getMessage());
                return $ex->getMessage();
            }
        }

    }

}
