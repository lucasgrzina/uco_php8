<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Repositories\NotaRepository;

use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        //parent::__construct();
    }

    public function index(HomeSliderRepository $sliderRepo,NotaRepository $notaRepo,Request $request,$locale = null)
    {
        //dd(app()->getLocale());

        $data = [
            'slides' => $sliderRepo->porSeccion('home'),
            'novedades' => $notaRepo->destacadosHome()
        ];

        return view('front.home', [
            'tituloPagina' => 'Home',
            'data' => $data
        ]);
    }

    public function cambiarIdioma($lang,$desde=null) {
        $desde = $desde ? $desde : 'home';
        if(in_array($lang, config('translatable.locales')))
        {
            app()->setLocale($lang);
            logger($desde);
            $cookie = \Cookie::make('uco_idioma', $lang, 518400);

            $desdePartes = explode('.',$desde);
            logger([$desde,$desdePartes]);
            $desde = $desdePartes[0];

            $to = $lang.'/';
            switch ($desde) {
                case 'home':
                    $to = trans('front.rutas.'.$desde);
                    break;
                case 'colecciones':
                    $to.= count($desdePartes) > 1 ? trans("front.rutas.{$desde}.{$desdePartes[1]}") :  trans("front.rutas.{$desde}.root");
                    break;
                case 'miCuenta':

                    $to.= trans("front.rutas.{$desde}.root") . (count($desdePartes) > 1 ? '/' . trans("front.rutas.{$desde}.{$desdePartes[1]}") : '');
                    break;
                case 'carrito':
                    $to .= trans('front.rutas.'.$desde.'.root') . '/'. trans('front.rutas.'.$desde.'.detalle');
                    break;
                case 'checkout':
                    $to .= trans('front.rutas.'.$desde.'.root');
                    break;
                default:
                    $to .= trans('front.rutas.'.$desde);
                    break;
            }


            // \Cart::clear();
            return redirect()->to($to)->withCookie($cookie);
        } else {
            dd("3");
        }
    }
}
