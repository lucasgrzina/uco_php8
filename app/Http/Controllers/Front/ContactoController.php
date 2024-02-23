<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;

class ContactoController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    public function index(Request $request,HomeSliderRepository $sliderRepo,$locale = null)
    {
        //dd(app()->getLocale());

        $data = [
            'slides' => $sliderRepo->porSeccion('contacto'),
            'loading' => false,
            'form' => [
                'submitted' => false,
                'nombre' => null,
                'apellido' => null,
                'email' => null,
                'pais' => null,
                'tel_prefijo' => null,
                'tel_numero' => null,
                'mensaje' => null,
                'acepto' => false,
                'recibir_info' => true
            ],
            'url_post_save' => route('service.contacto.guardar')
        ];

        return view('front.contacto', [
            'tituloPagina' => 'Contacto',
            'data' => $data
        ]);
    }
}
