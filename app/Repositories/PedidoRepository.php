<?php

namespace App\Repositories;

use App\Pedido;
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
            if ($mpPago->card) {
                $pedido->tarjeta = $mpPago->card->last_four_digits;
                $pedido->tarjeta_exp = $mpPago->card->expiration_month.'/'.$mpPago->card->expiration_year;
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

}
