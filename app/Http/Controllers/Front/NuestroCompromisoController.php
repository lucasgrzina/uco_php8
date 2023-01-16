<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Repositories\LegadosRepository;
use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;

class NuestroCompromisoController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repository;
    public function __construct()
    {
    }

    public function index(Request $request,HomeSliderRepository $sliderRepo,$locale = null,$slide=null)
    {
        //dd(app()->getLocale());

        $itemsSlide = trans('front.paginas.nuestroCompromiso.modulo2.slider');
        if ($slide == 'viticultura') {
            $itemsSlide = [
                $itemsSlide[2],$itemsSlide[3],$itemsSlide[1]
            ];
        } else if ($slide == 'calidad') {
            $itemsSlide = [
                $itemsSlide[3],$itemsSlide[1],$itemsSlide[2]
            ];
        }


        $data = [
            'slides' => $sliderRepo->porSeccion('nuestroCompromiso'),
            'items' => $itemsSlide

        ];





        return view('front.nuestro-compromiso', [
            'tituloPagina' => 'Nuestro compromiso',
            'data' => $data
        ]);
    }
}
