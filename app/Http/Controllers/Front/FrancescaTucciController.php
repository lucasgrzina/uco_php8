<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Repositories\HomeSliderRepository;
use App\Http\Controllers\AppBaseController;

class FrancescaTucciController extends AppBaseController
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
            'slides' => $sliderRepo->porSeccion('francescaTucci')
        ];

        return view('front.francesca-tucci', [
            'tituloPagina' => 'Francesca Tucci',
            'dataSection' => 'francesca-tucci',
            'data' => $data
        ]);
    }
}
