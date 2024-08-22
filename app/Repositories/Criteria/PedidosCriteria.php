<?php

namespace App\Repositories\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AdCriteria.
 *
 * @package namespace App\Repositories\Criteria;
 */
class PedidosCriteria implements CriteriaInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {

        if ($this->request->has('despacho') && $this->request->get('despacho',null) !== null)
        {
            if ($this->request->despacho === "MZA") {
                $model = $model->whereIn("cp",cpMza());
            } else {
                $model = $model->whereNotIn("cp",cpMza());
            }

        }
        if ($this->request->has('tipo_factura') && $this->request->get('tipo_factura',null) !== null)
        {
            $model = $model->where('tipo_factura',$this->request->tipo_factura);
        }
        if ($this->request->has('estado_id') && $this->request->get('estado_id',null) !== null)
        {
            $model = $model->where('estado_id',$this->request->estado_id);
        }
        if ($this->request->has('documento_sap') && $this->request->get('documento_sap',null) !== null)
        {
            $model = $model->where('documento_sap',$this->request->documento_sap);
        }
        if ($this->request->has('estatus_1') && $this->request->get('estatus_1',null) !== null)
        {
            $model = $model->where('estatus_1',$this->request->estatus_1);
        }
        if ($this->request->has('estatus_2') && $this->request->get('estatus_2',null) !== null)
        {
            $model = $model->where('estatus_2',$this->request->estatus_2);
        }
        if ($this->request->has('estatus_3') && $this->request->get('estatus_3',null) !== null)
        {
            $model = $model->where('estatus_3',$this->request->estatus_3);
        }
        return $model;
    }
}
