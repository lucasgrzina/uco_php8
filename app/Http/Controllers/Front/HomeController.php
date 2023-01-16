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

    public function cambiarIdioma($lang, Request $request) {

        if(in_array($lang, config('translatable.locales')))
        {
            app()->setLocale($lang);

            $cookie = \Cookie::make('uco_idioma', $lang, 518400);

            \Cart::clear();
            return redirect()->route('home',[$lang])->withCookie($cookie);
        } else {
            dd("3");
        }
    }
}
