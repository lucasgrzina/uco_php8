<?php
namespace App\Services;

use App\Aniada;
use App\Http\Controllers\AppBaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Uri;

class SAPService extends AppBaseController
{
    protected $repository;
    public function __construct()
    {

    }

    public function sincronizarProductos()
    {
        $host = env("SAP_HOST");
        $port = env("SAP_POST");

        $client = new Client([
            'verify' => false,
            'defaults' => [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30000
            ]
        ]);

        // Login credentials
        $body = [
            "UserName" => env("SAP_USERNAME"),
            "Password" => env("SAP_PASSWORD"),
            "CompanyDB" => env("SAP_COMPANY"),
        ];

        $uri = new Uri("https://{$host}:{$port}/b1s/v1/Login");

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\build_query([])), [
            'Content-Type' => 'application/json'
        ], json_encode($body));

        try {
            $response = $client->send($request);
            $resp = json_decode($response->getBody());
        }  catch (\Exception $ex) {
            logger("SAP - ". $ex->getMessage());
            dd("SAP - ". $ex->getMessage());
        }

        if($response->getStatusCode() != 200) {
            logger("SAP - No hubo loggin - ". $response->getStatusCode());
            die();
        }

        $param = [
            "\$select" => "ItemCode,ItemName,StockTotal,PriceList,Price,Currency,WhsCode,StockAlmacen"
        ];

        $uri = new Uri("https://{$host}:{$port}/b1s/v1/sml.svc/VU_ITEMINFO");

        $request = new Psr7\Request('GET', $uri->withQuery(\GuzzleHttp\Psr7\build_query($param)), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$resp->SessionId
        ]);

        try {
            $response = $client->send($request);
            $productos = json_decode($response->getBody());
        }  catch (\Exception $ex) {
            logger("SAP - ". $ex->getMessage());
        }

        foreach($productos->value as $producto)
        {
            if (($producto->PriceList == 2 || $producto->PriceList == 1) && $producto->ItemCode != "")
            {
                $aniada = Aniada::where('sku', $producto->ItemCode)->first();

                if ($aniada != null) {
                    //logger($aniada);
                    $aniada->stock = $producto->StockAlmacen;
                    if($producto->PriceList == 2) {
                        $aniada->precio_usd = $producto->Price;
                    } else {
                        $aniada->precio_pesos = $producto->Price;
                    }
                    $aniada->save();
                }
            }
        }
    }
}
