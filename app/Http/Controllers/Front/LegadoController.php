<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Repositories\NotaRepository;

use App\Repositories\LegadosRepository;
use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;

class LegadoController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repository;
    public function __construct(LegadosRepository $repo)
    {
        $this->repository = $repo;
    }

    public function index(Request $request,HomeSliderRepository $sliderRepo,$locale = null)
    {
        //dd(app()->getLocale());

        $data = [
            'slides' => $sliderRepo->porSeccion('legado'),
            'items' => $this->repository->activos()
        ];

        return view('front.legado', [
            'tituloPagina' => 'Legado',
            'data' => $data
        ]);
    }
}
