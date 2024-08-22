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
class VinosCriteria implements CriteriaInterface
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

        if ($this->request->has('titulo') && $this->request->get('titulo',null) !== null)
        {
            $model = $model->whereHas('translations',function($query) {
                $query
                    ->where('titulo','like', '%' . $this->request->get('titulo') . '%' );
            });

        }
        if ($this->request->has('peso') && $this->request->get('peso',null) !== null)
        {
            $model = $model->where('peso',$this->request->peso);
        }
        if ($this->request->has('largo') && $this->request->get('largo',null) !== null)
        {
            $model = $model->where('largo',$this->request->largo);
        }
        if ($this->request->has('ancho') && $this->request->get('ancho',null) !== null)
        {
            $model = $model->where('ancho',$this->request->ancho);
        }
        if ($this->request->has('alto') && $this->request->get('alto',null) !== null)
        {
            $model = $model->where('alto',$this->request->alto);
        }
        return $model;
    }
}
