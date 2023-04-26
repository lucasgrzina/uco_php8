<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\AppBaseController;


class FrontController extends AppBaseController
{
    public function __construct()
    {
    }

    public function politicasPrivacidad ($locale) {
        $data = [];
        return view('front.estaticas.politicas-privacidad', [
            'tituloPagina' => 'Políticas de privacidad',
            'dataSection' => 'mis-pedidos',
            'data' => $data
        ]);
    }
    public function terminosCondiciones ($locale) {
        $data = [];
        return view('front.estaticas.terminos-condiciones', [
            'tituloPagina' => trans('front.modulos.tyc.titulo'),
            'dataSection' => 'mis-pedidos',
            'hideAgeGate' => true,
            'data' => $data
        ]);
    }
}
