<?php

namespace App\Http\Controllers;


use App\Pedido;

use App\Services\MPService;
use App\Helpers\StorageHelper;
use App\Services\ApiRolService;
use App\Services\ApiSmsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use MercadoPago;
class TestController extends AppBaseController
{
    public function doppler() {

        $listId = 28395666;
        $response = dopplerSuscribirALista("lucasgrzina+50@gmail.com","Lucas",$listId);
        return response()->json(($response));


    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function sms(ApiSmsService $smsService) {
        try
        {
            return $smsService->enviarCodigo('11','34290838');

        }
        catch(\Exception $ex)
        {
            \Log::info($ex->getMessage());
        }
    }

    public function mpPreferenciaPago() {
        try
        {
            app()->setLocale('es');
            $pedido = Pedido::find(58);

            MercadoPago\SDK::setAccessToken(config('services.mercadopago.access_token'));

            $preference = new MercadoPago\Preference();

            // del artículo vendido
            $item = new MercadoPago\Item();
            $item->title = 'Magia del Uco - Pedido ' . $pedido->id;
            $item->quantity = 1;
            $item->currency_id = 'ARS';
            $item->unit_price =  $pedido->total;
            $preference->items = array($item);

            //del comprador
            /*$payer = new MercadoPago\Payer();
            $payer->name = $name;
            $payer->email = $email;
            $preference->payer = $payer;
            */
            // las url de retorno a donde mercadolibre nos redigirá despues de terminar el proceso de pago
            // IMPORTANTE: No utilizar IPs en las url como 127.0.0.1 o 10.1.1.10 porque el SDK marcará un error
            $preference->back_urls = array(
              "success" => routeIdioma('checkout.gracias', [md5($pedido->id)]),
              "failure" => routeIdioma('checkout.gracias', [md5($pedido->id)]),
              "pending" => routeIdioma('checkout.gracias', [md5($pedido->id)])
            );
            $preference->external_reference= 'WEB_'.$pedido->id;
            $preference->payment_methods = [
                "excluded_payment_types"=> [
                    [
                        "id" => "ticket"
                    ]
                ],

            ];
            $preference->save();

            return $this->sendResponse($preference->init_point, trans('admin.success'));
        }
        catch(\Exception $ex)
        {
            \Log::info($ex->getMessage());
        }
    }

    public function leerS3() {

        $path = env('AMAZON_S3_FOLDER').'/tmp/test2.png';
        $img = StorageHelper::contenido('tmp/1605205406-Premio-01.jpg','uploads_local');

        StorageHelper::put(
            $path,
            $img
        );

        //\Log::info(StorageHelper::url(env('AMAZON_S3_FOLDER').'/tmp/1612980492-2021-01-18-16-32-localhost.png','uploads'));
        $files = StorageHelper::archivos(env('AMAZON_S3_FOLDER').'/tmp');
        return $files;
    }
}
