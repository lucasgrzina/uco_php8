<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Repositories\NotaRepository;

use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;

class NovedadesController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repository;
    public function __construct(NotaRepository $repo)
    {
        $this->repository = $repo;
    }

    public function index(Request $request,HomeSliderRepository $sliderRepo,$locale = null,$id=null)
    {
        //dd(app()->getLocale());

        $data = [
            'slides' => $sliderRepo->porSeccion('novedades'),
            'novedades' => [
                'notaCompleta' => false,
                'actual' => null,
                'items' => $this->repository->recientes(),
                'destacados' => $this->repository->destacados(),
            ],
        ];
        if (!$id) {
            $actual = $data['novedades']['items']->first();
        } else {
            $actual = $this->repository->find($id);
        }

        if (!$actual) {
            return redirect()->to(routeIdioma('home'));
        }

        $data['novedades']['actual'] = $actual;
        //$id = $id ? $id : $data['destacados']
        //$data['actual'] =


        return view('front.novedades', [
            'tituloPagina' => trans('front.paginas.novedades.titulo'),
            'data' => $data
        ]);
    }
}
