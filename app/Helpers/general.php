<?php

use Carbon\Carbon;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use App\Configuraciones;
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
    $configuraciones = Configuraciones::whereIn('clave',['DOLAR_UPS'])->pluck('valor','clave')->toArray();
    $formateado = isset($configuraciones['DOLAR_UPS']) && $configuraciones['DOLAR_UPS'] ? str_replace(",",".",$configuraciones['DOLAR_UPS']) : config('constantes.dolarUPS');
    return $formateado;
}

function esCPMza($codigoPostal) {
    $cpMza = [
        '5500',
        '5501',
        '5502',
        '5503',
        '5505',
        '5507',
        '5508',
        '5509',
        '5510',
        '5511',
        '5513',
        '5514',
        '5515',
        '5518',
        '5519',
        '5520',
        '5521',
        '5522',
        '5524'
    ];
    return in_array($codigoPostal,$cpMza);
}

function obtenerDolarOficial() {
    if (env('APP_ENV','local') === 'local') {
        return (float)env('COTIZACION_DOLAR','176.83');
    }

    $fecha = Carbon::yesterday()->format('Y-m-d');
    if (\Cache::has('dolar-'.$fecha)) {
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

function logInfo($input) {
    try {
        \Log::info($input);
    } catch (\Exception $e) {

    }
}
if (!function_exists('repo')) {
    function repo($className)
    {
        return app('App\Repositories\\'.$className.'Repository');
    }
}
if (!function_exists('servicio')) {
    function servicio($className)
    {
        return app('App\Services\\'.$className.'Service');
    }
}
