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
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function randomPassword2($password_length=6) {
    $uppercase_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';

    // Genera una contraseña aleatoria que contenga al menos una letra mayúscula y al menos un número
    do {
      $password = '';
      $has_uppercase_letter = false;
      $has_number = false;

      // Genera una contraseña aleatoria de longitud $password_length
      for ($i = 0; $i < $password_length; $i++) {
        $characters = '';

        // Agrega letras mayúsculas si aún no se ha agregado ninguna
        if (!$has_uppercase_letter || !$has_number) {
          if (!$has_uppercase_letter) {
            $characters .= $uppercase_letters;
          }

          if (!$has_number) {
            $characters .= $numbers;
          }
        } else {
          // Si ya se ha agregado al menos una letra mayúscula y un número, se pueden agregar letras minúsculas y caracteres especiales
          $characters = $uppercase_letters . strtolower($uppercase_letters) . $numbers;
        }

        // Agrega un caracter aleatorio del conjunto de caracteres disponibles
        $password .= $characters[rand(0, strlen($characters) - 1)];

        // Verifica si se ha agregado una letra mayúscula y un número a la contraseña
        if (preg_match('/[A-Z]/', $password)) {
          $has_uppercase_letter = true;
        }

        if (preg_match('/[0-9]/', $password)) {
          $has_number = true;
        }
      }
    } while (!$has_uppercase_letter || !$has_number);

    return $password;
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
