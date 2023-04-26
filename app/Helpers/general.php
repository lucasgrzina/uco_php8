<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Uri;

function routeIdioma($name,$params = []) {
    $codigoPais = app()->getLocale();
    array_unshift($params,$codigoPais);
    return route($name,$params);
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '1234567890';
    $pass = array(); //remember to declare $pass as an array

    while (count($pass) < 6) {
        $pos = rand(0, strlen($alphabet) - 1);
        $n = $alphabet[$pos];
        $pass[] = $n;

        $pos = rand(0, strlen($alphabet) - 1);
        $n = $alphabet[$pos];
        $pass[] = strtoupper($n);

        $pos = rand(0, strlen($numbers) - 1);
        $n = $numbers[$pos];
        $pass[] = $n;
    }

    return implode($pass); //turn the array into a string
}


function strEmpiezaCon($str,$comp) {
    return substr($str,0,strlen($comp)) === $comp;
}

function formatoFechaNota($fecha) {
    return Carbon::parse($fecha)->format('d.M');
}

if (!function_exists('formatoImporte')) {
    function formatoImporte($importe, $signo = 'AR$')
    {
        $importe = number_format($importe, 2, ',', '.');
        return ($signo ? $signo . ' ' : '') . $importe;
    }
}

function obtenerDolarUPS() {
    return config('constantes.dolarUPS');
}

function obtenerDolarOficial() {
    if (env('APP_ENV','local') === 'local') {
        return (float)env('COTIZACION_DOLAR','176.83');
    }

    $fecha = Carbon::yesterday()->format('Y-m-d');
    if (\Cache::has('dolar-'.$fecha)) {
        logger('dolar cache');
        return \Cache::get('dolar-'.$fecha);
    }

    $client = new Client([]);

    $uri = new Uri("https://api.estadisticasbcra.com/usd_of");

    $request = new Psr7\Request('GET', $uri->withQuery(\GuzzleHttp\Psr7\Query::build(['d' => $fecha])), [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.env('BCRA_TOKEN')
    ]);

    try {
        $response = $client->send($request);
        $resp = json_decode($response->getBody());
        //$neededObject = array_column($resp, null, 'd')[$fecha] ?? false;
        \Cache::add('dolar-'.$fecha, $resp[count($resp) - 1]->v, 1440);

        return $resp[count($resp) - 1]->v;
    }  catch (\Exception $ex) {
        logger("Dolar - ". $ex->getMessage());
        return 0;
    }
}

function assetComodin($asset) {
    return strEmpiezaCon($asset,'_asset.') ? asset(str_replace('_asset.','',$asset)) : $asset;
}
