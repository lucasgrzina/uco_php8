<?php

namespace App\Http\Controllers\Front;

use App\Configuraciones;

use Illuminate\Http\Request;
use App\Repositories\VinosRepository;
use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;

class ColeccionesController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    public function index(Request $request,HomeSliderRepository $sliderRepo) {
        $data = [
            'slides' => $sliderRepo->porSeccion('colecciones'),
        ];

        return view('front.colecciones-root', [
            'tituloPagina' => trans('front.paginas.colecciones.root.titulo'),
            'data' => $data
        ]);
    }

    public function tucci(HomeSliderRepository $sliderRepo, VinosRepository $vinosRepo, Request $request, $locale = null,$id = null, $slug = null)
    {
        //dd(app()->getLocale());
        $vinos = $vinosRepo->porColeccion('FT');

        $actual = null;


        if ($id) {
            $actual = $vinos->find($id);
        }

        if (!$actual) {
            $actual = $vinos->first();
        }



        $data = [
            'slides' => $sliderRepo->porSeccion('coleccionesTucci'),
            'vinos' => $vinos,

            'actual' => $actual,
            'aniadaActual' => $actual ? $actual->aniadas->first() : null,
            'loading' => false,
            'routePrefix' => 'tucci'
        ];

        return view('front.colecciones', [
            'tituloPagina' => trans('front.paginas.colecciones.tucci.titulo'),
            'dataSection' => 'francesca-tucci',
            'data' => $data
        ]);
    }

    public function interwine(Request $request,HomeSliderRepository $sliderRepo, VinosRepository $vinosRepo,$locale = null,$id = null, $slug = null)
    {
        //dd(app()->getLocale());
        $vinos = $vinosRepo->porColeccion('IN');
        $actual = null;
        $configuraciones = Configuraciones::whereIn('clave',['COMPRAS_SUPERIORES'])->pluck('valor','clave')->toArray();
        //dd($configuraciones);
        if ($id) {
            $actual = $vinos->find($id);
        }

        if (!$actual) {
            $actual = $vinos->first();
        }

        //dd($actual->toArray());

        $data = [
            'slides' => $sliderRepo->porSeccion('coleccionesInterwine'),
            'vinos' => $vinos,
            'actual' => $actual,
            'aniadaActual' => $actual ? $actual->aniadas->first() : null,
            'loading' => false,
            'routePrefix' => 'interwine',
            'configuraciones' =>$configuraciones
        ];

        return view('front.colecciones', [
            'tituloPagina' => trans('front.paginas.colecciones.interwine.titulo'),
            'dataSection' => 'interwine',
            'data' => $data
        ]);
    }

    public function magiaUco(Request $request,HomeSliderRepository $sliderRepo, VinosRepository $vinosRepo,$locale = null,$id = null, $slug = null)
    {
        //dd(app()->getLocale());
        $vinos = $vinosRepo->porColeccion('MU');
        $actual = null;
        $configuraciones = Configuraciones::whereIn('clave',['COMPRAS_SUPERIORES'])->pluck('valor','clave')->toArray();
        //dd($configuraciones);
        if ($id) {
            $actual = $vinos->find($id);
        }

        if (!$actual) {
            $actual = $vinos->first();
        }

        //dd($actual->toArray());

        $data = [
            'slides' => $sliderRepo->porSeccion('coleccionesMagiaUco'),
            'vinos' => $vinos,
            'actual' => $actual,
            'aniadaActual' => $actual ? $actual->aniadas->first() : null,
            'loading' => false,
            'routePrefix' => 'magiaUco',
            'configuraciones' =>$configuraciones
        ];

        return view('front.colecciones', [
            'tituloPagina' => trans('front.paginas.colecciones.magiaUco.titulo'),
            'dataSection' => 'interwine',
            'data' => $data
        ]);
    }

    public function magiaUcoNotable(Request $request,HomeSliderRepository $sliderRepo, VinosRepository $vinosRepo,$locale = null,$id = null, $slug = null)
    {
        //dd(app()->getLocale());
        $vinos = $vinosRepo->porColeccion('MN');
        $actual = null;
        $configuraciones = Configuraciones::whereIn('clave',['COMPRAS_SUPERIORES'])->pluck('valor','clave')->toArray();
        //dd($configuraciones);
        if ($id) {
            $actual = $vinos->find($id);
        }

        if (!$actual) {
            $actual = $vinos->first();
        }

        //dd($actual->toArray());

        $data = [
            'slides' => $sliderRepo->porSeccion('coleccionesMagiaUcoNotable'),
            'vinos' => $vinos,
            'actual' => $actual,
            'aniadaActual' => $actual ? $actual->aniadas->first() : null,
            'loading' => false,
            'routePrefix' => 'magiaUcoNotable',
            'configuraciones' =>$configuraciones
        ];

        return view('front.colecciones', [
            'tituloPagina' => trans('front.paginas.colecciones.magiaUcoNotable.titulo'),
            'dataSection' => 'interwine',
            'data' => $data
        ]);
    }
}
