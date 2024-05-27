<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Repositories\NotaRepository;

use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\NuestroCompromisoRepository;

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

    public function index(HomeSliderRepository $sliderRepo,NotaRepository $notaRepo,Request $request,NuestroCompromisoRepository $nuestroRepo,$locale = null)
    {
        //dd(app()->getLocale());

        $data = [
            'slides' => $sliderRepo->porSeccion('home'),
            'novedades' => $notaRepo->destacadosHome(),
            'nuestroCompromiso' => $nuestroRepo->pluck('imagen_home','codigo')->toArray()
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
            $cookie = \Cookie::make('uco_idioma', $lang, 518400);

            $desdePartes = explode('.',$desde);
            $desde = $desdePartes[0];

            $to = $lang.'/';
            switch ($desde) {
                case 'home':
                    $to = trans('front.rutas.'.$desde);
                    break;
                 case 'nuestroCompromiso':
                    //dd([$desde,trans("front.rutas.{$desde}.{$desdePartes[1]}"),request()->all()]);
                    $params = explode('|',request()->get('params'));

                    if (count($params) > 1) {
                        switch($params[1]) {
                            case 'certificacoes':
                                case 'certifications':
                                    case 'certificaciones':
                                        $keySeccion = 'certificaciones';
                                        break;
                            case 'calidad':
                                case 'quality':
                                    case 'qualidade':
                                        $keySeccion = 'calidad';
                                        break;
                            case 'viticultura':
                                case 'viticulture':
                                    $keySeccion = 'viticultura';
                                    break;

                            case 'nuestra-gente':
                                case 'our-people':
                                    case 'nossa-gente':
                                        $keySeccion = 'nuestra-gente';
                                        break;
                        }
                        $keySeccion = trans("front.paginas.home.compromiso.secciones.{$keySeccion}");
                        $to.= trans("front.rutas.{$desde}").'/'.$keySeccion;
                    } else {
                        $to.= trans("front.rutas.{$desde}");
                    }
                    logger($to);
                    break;
                case 'colecciones':
                    //dd([$desde,trans("front.rutas.{$desde}.{$desdePartes[1]}"),request()->all()]);
                    if (count($desdePartes) > 1) {
                        $params = explode('|',request()->get('params'));
                        if (count($params) > 1) {
                            $to.= trans("front.rutas.{$desde}.{$desdePartes[1]}").'/'.$params[1].'/'.$params[2];
                        } else {
                            $to.= trans("front.rutas.{$desde}.{$desdePartes[1]}");
                        }

                    } else {
                        $to.= trans("front.rutas.{$desde}.root");
                    }

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
                case 'terminosCondiciones':
                    $to .= trans('front.rutas.tyc');
                    break;
                case 'politicasPrivacidad':
                    $to .= trans('front.rutas.pp');
                    break;
                case 'novedades':
                    $params = explode('|',request()->get('params'));
                    if (count($params) > 1) {
                        $to.= trans("front.rutas.{$desde}").'/'.$params[1];
                    } else {
                        $to.= trans("front.rutas.{$desde}");
                    }
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
