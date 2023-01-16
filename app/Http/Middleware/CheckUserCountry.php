<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\PaisesRepository;

class CheckUserCountry
{

    public function handle($request, Closure $next)
    {
        $repo = \App::make(PaisesRepository::class);
        //$response = $next($request);
        $locale = request()->segment(1);
        $codigos = $repo->codigos();

        if (in_array($locale,$codigos)) {
            return $next($request);
        } else {
            return response()->view('front.errors', ['error' => 'No existe el pais'], 500);
            //throw new \Exception("Error Processing Request", 500);            
        }
    }
    

}