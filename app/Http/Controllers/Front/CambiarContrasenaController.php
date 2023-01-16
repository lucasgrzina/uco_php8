<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\DB;
use App\Repositories\BannersRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Front\CambiarPasswordRequest;



class CambiarContrasenaController extends AppBaseController
{

    public function index($pais, BannersRepository $bannersRepo)
    {
        $paisId = paisActual($pais)->id;
        $this->data = [
            'titulo' => 'Cambiar contraseña',
            'subtituloSeccion' => 'Ingresa una nueva contraseña.',
            'form' => [
                'password' => null
            ],
            'enviando' => false,
            'enviadoOk' => false,
            'url_guardar' => routeIdioma('cambiarContrasena.guardar')
        ];
        return view('front.contrasena', ['data' => $this->data]);
    }

    public function guardar($pais,CambiarPasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $registrado = auth()->user();
            $registrado->password = $request->password;
            $registrado->save();   
            DB::commit();
            return $this->sendResponse(['message' => 'Tu contraseña fué modificada con éxito.'],''); 
        } catch (\Exception $e) {
            DB::rollback();
            $this->sendError($e->getMessage(),$e->getCode());
        }

    }    
  
}
