<?php
namespace App\Services;

use Exception;
use App\Packaging;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use \Ups\Entity\Shipment;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Database\Eloquent\Collection;

class MPService extends AppBaseController
{
    public function __construct()
    {

    }

    public function buscarPagoPorPedidoId($pedidoId)
    {

        try {

            $configMP = config('services.mercadopago');

            $client = $this->getClient();
            $uri = new Uri('https://api.mercadopago.com/v1/payments/search');

            $filters =  [
                'external_reference' => $configMP['ep_prefix'].$pedidoId,
                'sort' => 'date_created',
                'criteria'=>'desc'
            ];

            $request = new Request('GET', $uri->withQuery(Query::build($filters)), [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $configMP['access_token']
            ], json_encode($filters));


            $response = $client->send($request);
            if ($response->getStatusCode() == 200) {
                $contents = json_decode((string) $response->getBody());
                if ($contents->paging->total > 0) {
                    return $contents->results[0];
                } else {
                    throw new \Exception("No se encontró el pago en MP", 404);
                }
            } else {
                throw new \Exception("No se encontró el pago en MP", 404);
            }


        } catch (Exception $e) {
            throw $e;
            //var_dump($e->getMessage());
        }
    }

    protected function getClient() {
        return new Client([
            'defaults' => [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout'         => 30000
                //'exceptions' => false
            ]
        ]);
    }
}
