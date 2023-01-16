<?php

namespace App\Http\Middleware;

use Closure;


class CheckoutMiddleware
{
    public function handle($request, Closure $next)
    {
        if (app('auth')->guest()) {
            \Session::put('attemptedUrlFront', \URL::current());
            return redirect()->to(routeIdioma('login'));
        }

        if (\Cart::getContent()->count() < 1) {
            return redirect()->to(routeIdioma('home'));
        }

        return $next($request);
    }
}
