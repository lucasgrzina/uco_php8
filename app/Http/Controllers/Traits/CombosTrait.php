<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\RetailsRepository;
use App\Repositories\SucursalesRepository;
use App\Repositories\ProveedoresRepository;
use Prettus\Repository\Criteria\RequestCriteria;

trait CombosTrait
{
    public function comboRetails(Request $request)
    {
        $repo = \App::make(RetailsRepository::class);
        $repo->pushCriteria(new RequestCriteria($request));
        $data = $repo->scopeQuery(function($q) use($request){
            return $q->wherePaisId($request->pais_id);
        })->all(['id','nombre','pais_id'])->toArray();

        return $data;
    }
    public function comboSucursales(Request $request)
    {
        $repo = \App::make(SucursalesRepository::class);
        $repo->pushCriteria(new RequestCriteria($request));
        $data = $repo->scopeQuery(function($q) use($request){
            return $q->whereRetailId($request->retail_id);
        })->all(['id','nombre','retail_id'])->toArray();

        return $data;
    }
    
    public function comboProveedores(Request $request)
    {
        $repo = \App::make(ProveedoresRepository::class);
        $repo->pushCriteria(new RequestCriteria($request));
        $data = $repo->all(['id','razon_social','cuit'])->toArray();

        return $data;
    }    
}