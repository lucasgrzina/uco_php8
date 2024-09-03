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
class RegistradoCriteria implements CriteriaInterface
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
        if ($this->request->has('pais_id') && $this->request->get('pais_id',null) !== null)
        {
            $model = $model->where('pais_id',$this->request->pais_id);
        }
        if ($this->request->has('fecha_desde') && $this->request->get('fecha_desde',null) !== null){
            $model = $model->whereDate('created_at','>=',\Str::limit($this->request->get('fecha_desde'),10,''));
        }
        if ($this->request->has('fecha_hasta') && $this->request->get('fecha_hasta',null) !== null){
            $model = $model->whereDate('created_at','<=',\Str::limit($this->request->get('fecha_hasta'),10,''));
        }

        return $model;
    }
}
