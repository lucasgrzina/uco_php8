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

    public function actualizarPagoPedido($pedidoGuid, $estado, $estado_detalle = null)
    {
        try {
            $modelo = $this->model->newQuery()->where(\DB::raw('md5(id)'), $pedidoGuid)->first();
            $estadoActual = $modelo->estado_id;

            switch ($estado) {
                case 'success':
                case 'successwithwarning':
                case 'approved':
                    $modelo->ult_estado_pago = 'aprobado';
                    $modelo->estado_id = 1;
                    break;
                case 'rejected':
                case 'cancelled':
                case 'failure':
                    $modelo->estado_id = -1;
                    $modelo->ult_estado_pago = 'rechazado';
                    break;
                default:
                    $modelo->estado_id = 0;
                    $modelo->ult_estado_pago = $estado;
                    break;
            }
            $modelo->ult_estado_pago_detalle = ($estado_detalle ? $estado . ' - ' . $estado_detalle : $estado_detalle);
            $modelo->save();

            if ($modelo->estado_id == 1 && $estadoActual != 1) {
                // El pago sufrio un cambio de estado a aprobado
                //$modelo->registrado->enviarNotificacionPedidoConfirmado($modelo);
            }

            return $modelo;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }    
}
