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
class UserCriteria implements CriteriaInterface
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

        if ($this->request->has('rol') && $this->request->get('rol',null) !== null)
        {
            if ($this->request->get('rol')>0){
                return $model->whereHas('roles',function($query) {
                    $query->where('role_id',$this->request->get('rol'));
                });
            }
        }
        return $model;
    }
}
