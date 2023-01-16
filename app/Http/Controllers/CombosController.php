<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Traits\CombosTrait;
//use App\Repositories\ProveedorRepository;
//use App\Repositories\RubroRepository;


class CombosController extends AppBaseController
{
	use CombosTrait;

	public function retails(Request $request)
	{
		$data = static::comboRetails($request);
		return $this->sendResponse($data, 'La operacion finalizo con exito');
	}

	public function sucursales(Request $request)
	{
		$data = static::comboSucursales($request);
		return $this->sendResponse($data, 'La operacion finalizo con exito');
	}	

	public function proveedores(Request $request)
	{
		$data = static::comboProveedores($request);
		return $this->sendResponse($data, 'La operacion finalizo con exito');
	}		
}
