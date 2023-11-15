<?php

namespace App\Repositories\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProveedorCriteria.
 *
 * @package namespace App\Repositories\Criteria;
 */
class HomeSliderCriteria implements CriteriaInterface
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

        if ($this->request->has('seccion') && $this->request->get('seccion',null) !== null)
        {
            $model = $model->whereSeccion($this->request->seccion);
        }
        return $model;
    }
}
