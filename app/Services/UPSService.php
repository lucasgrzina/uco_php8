<?php
namespace App\Services;

use Exception;
use App\Packaging;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Uri;
use App\Http\Controllers\AppBaseController;
use Illuminate\Database\Eloquent\Collection;

class UPSService extends AppBaseController
{
    protected $ratingClient;
    protected $client;
    protected $config;
    protected $cpMza = [];
    protected $shipperMza = [];
    protected $shipperBsAs = [];
    protected $upsUserIdMza;
    protected $upsUserIdBsAs;

    public function __construct()
    {
        $this->config = config('ups');
        $this->client = new Client([
            'verify' => false,
            'defaults' => [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30000
            ]
        ]);
        $this->cpMza = [
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

        $this->upsUserIdMza = config('ups.UPS_USERID');
        $this->upsUserIdBsAs = config('ups.UPS_USERID');

        $this->shipperMza = [
            "Name" => config('ups.DIRECCION_DESDE_MZA.NOMBRE'),
            "AttentionName" => config('ups.DIRECCION_DESDE_MZA.NOMBRE'),
            "ShipperNumber" => "{$this->upsUserIdMza}",
            "Address" => [
                "AddressLine" => config('ups.DIRECCION_DESDE_MZA.DIRECCION'),
                "City" => config('ups.DIRECCION_DESDE_MZA.PROVINCIA'),
                //"StateProvinceCode" => config('ups.DIRECCION_DESDE.PROVINCIA'),
                "PostalCode" => config('ups.DIRECCION_DESDE_MZA.CODIGO_POSTAL'),
                "CountryCode" => config('ups.DIRECCION_DESDE_MZA.PAIS'),
            ]
        ];

        $this->shipperBsAs = [
            "Name" => config('ups.DIRECCION_DESDE_BSAS.NOMBRE'),
            "AttentionName" => config('ups.DIRECCION_DESDE_BSAS.NOMBRE'),
            "ShipperNumber" => "{$this->upsUserIdBsAs}",
            "Address" => [
                "AddressLine" => config('ups.DIRECCION_DESDE_BSAS.DIRECCION'),
                "City" => config('ups.DIRECCION_DESDE_BSAS.PROVINCIA'),
                //"StateProvinceCode" => config('ups.DIRECCION_DESDE_BSAS.PROVINCIA'),
                "PostalCode" => config('ups.DIRECCION_DESDE_BSAS.CODIGO_POSTAL'),
                "CountryCode" => config('ups.DIRECCION_DESDE_BSAS.PAIS'),
            ]
        ];
    }

    public function cotizarEnvio($codigoPais, $codigoPostal, $calle, $ciudad, $productos)
    {
        try {
            $upsUserId = $this->esCPMza($codigoPostal) ? $this->upsUserIdMza : $this->upsUserIdBsAs;
            $packages = [];
            $totalWeight = 0;
            $cajasEnvio = $this->calcularCajas($productos);

            foreach($cajasEnvio as $caja)
            {
                $package = [
                    "PackagingType" => ["Code" => "02", "Description" => "Package"],
                    "Dimensions" => [
                        "UnitOfMeasurement" => ["Code" => "CM"],
                        "Length" => $caja->largo,
                        "Width" => $caja->ancho,
                        "Height" => $caja->alto,
                    ],
                    "PackageWeight" => [
                        "UnitOfMeasurement" => ["Code" => "KGS"],
                        "Weight" => "{$caja->peso}",
                    ],
                ];
                $totalWeight+= (float)$caja->peso;
                $packages[] = $package;
            }

            $body = [
                "RateRequest" => [
                    "Request" => [
                        "RequestOption" => "Rate",
                        "SubVersion" => "v2205",
                        "TransactionReference" => ["CustomerContext" => 'Rate'],
                    ],
                    "Shipment" => [
                        "NegotiatedRatesIndicator" => "Y",
                        "ShipmentRatingOptions" => ["NegotiatedRatesIndicator" => "Y"],
                        "Shipper" => $this->esCPMza($codigoPostal) ? $this->shipperMza : $this->shipperBsAs,
                        "ShipTo" => [
                            "Name" => $calle,
                            "Address" => [
                                "AddressLine" =>$calle,
                                "City" => $ciudad,
                                //"StateProvinceCode" => $ciudad,
                                "PostalCode" => $codigoPostal,
                                "CountryCode" => $codigoPais,
                            ],
                        ],
                        "ShipFrom" => $this->esCPMza($codigoPostal) ? \Arr::except($this->shipperMza,['ShipperNumber','AttentionName']) : \Arr::except($this->shipperBsAs,['ShipperNumber','AttentionName']),
                        "ShipmentTotalWeight" => [
                            "UnitOfMeasurement" => [
                                "Code" => "KGS",
                                "Description" => "KILOS",
                            ],
                            "Weight" => "{$totalWeight}",
                        ],
                        //"NumOfPieces" => "1",
                        "Package" => $packages,
                        "PaymentDetails" => [
                            "ShipmentCharge" => [
                                "Type" => "01",
                                "BillShipper" => ["AccountNumber" => "{$upsUserId}"],
                            ],
                        ],
                        "DeliveryTimeInformation" => [
                            "PackageBillType" => "03"
                        ],
                    ],
                ],
            ];

            $respuesta = [];
            // Get shipment info

            $token = $this->getAccessToken();

            $uri = new Uri($this->config['URL_RATING']);

            $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ],json_encode($body,JSON_HEX_QUOT));


            try {
                $response = $this->client->send($request);
                $resultado = json_decode($response->getBody());

            }  catch (\Exception $ex) {
                $response = json_decode($ex->getResponse()->getBody()->getContents(), true);
                throw new \Exception($ex->getMessage(), 1);

                //\Log::channel('consola')->info("UPS - ". $ex->getMessage());
                //dd("SAP - ". $ex->getMessage());
            }


            $dolarOficial = obtenerDolarUPS();
            return [
                'cotizacion' => $dolarOficial,
                'pesos' => $resultado->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue * $dolarOficial,
                'dolares' => $resultado->RateResponse->RatedShipment->NegotiatedRateCharges->TotalCharge->MonetaryValue
                //'pesos' => ($resultado->RatedShipment[0]->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue * $dolarOficial),
                //'dolares' => $resultado->RatedShipment[0]->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue
            ];
        } catch (Exception $e) {
            throw $e;
            //var_dump($e->getMessage());
        }
    }

    public function calcularCajas($productos)
    {
        $cajas = [];
        if(count($productos) > 6) {
            $resto = count($productos) % 6;
            $cajas = $this->obtenerCajasPorUnidad($productos, 6);
            if($resto > 0) {
                $cajas = $cajas->push($this->obtenerCajaIndividual($productos)[0]);
            }
        } else {
            $cajas = $this->obtenerCajaIndividual($productos);
        }

        return $cajas;
    }

    public function obtenerCajaIndividual($productos)
    {
        if (count($productos) > 1) {
            $caja = Packaging::where('unidades', '>=', count($productos))->orderBy('unidades')->take(1)->get();
        } else{
            $caja = Packaging::where('unidades', 2)->orderBy('unidades')->take(1)->get();
        }

        foreach($productos as $producto)
        {
            $caja[0]->peso += (float) ($producto->vino ? $producto->vino->peso : $producto->aniada->vino->peso);
        }

        return $caja;
    }

    public function obtenerCajasPorUnidad(&$productos, $unidad)
    {
        $cantidadCajas = count($productos) / $unidad;

        $cajas = Packaging::where('unidades', $unidad)->get();
        for($x = 0; $x < intval($cantidadCajas); $x++)
        {
            if($x > 0) {
                $cajaIndividual = Packaging::where('unidades', $unidad)->first();
                $cajas->push($cajaIndividual);
            }
        }

        $unidadesUsadas = 0;
        $llenoCaja = 0;
        foreach($productos as $key => $producto)
        {
            $cajas[$llenoCaja]->peso += (float) $producto->vino->peso;
            unset($productos[$key]);
            $unidadesUsadas++;

            if($unidadesUsadas == $unidad) {
                $llenoCaja++;
                $unidadesUsadas = 0;
                if($llenoCaja >= intval($cantidadCajas)) {
                    break;
                }
            }
        }

        return $cajas;
    }

    public function generarEnvio($numeroOrden, $productos, $direccion, $ciudad, $codigoPais, $codigoPostal, $nombreDestinatario, $apellidoDestinatario, $emailDestinatario, $telefonoDestinatario)
    {
        $upsUserId = $this->esCPMza($codigoPostal) ? $this->upsUserIdMza : $this->upsUserIdBsAs;
        $packages = [];
        $totalWeight = 0;
        $cajasEnvio = $this->calcularCajas($productos);
        foreach($cajasEnvio as $caja)
        {
            $package = [
                "Description" => "Vinos - FRAGIL",
                "Packaging" => ["Code" => "02", "Description" => "Package"],
                "Dimensions" => [
                    "UnitOfMeasurement" => ["Code" => "CM"],
                    "Length" => $caja->largo,
                    "Width" => $caja->ancho,
                    "Height" => $caja->alto,
                ],
                "PackageWeight" => [
                    "UnitOfMeasurement" => ["Code" => "KGS"],
                    "Weight" => "{$caja->peso}",
                ],
            ];
            $totalWeight+= (float)$caja->peso;
            $packages[] = $package;
        }

        $body = [
            "ShipmentRequest" => [
                "Request" => [
                    "RequestOption" => "nonvalidate",
                    "SubVersion" => "v2205",
                    "TransactionReference" => [
                        "CustomerContext" => 'Orden - '.$numeroOrden
                    ],
                ],
                "Shipment" => [
                    "Description" => 'Orden - '.$numeroOrden,
                    "Shipper" => $this->esCPMza($codigoPostal) ? $this->shipperMza : $this->shipperBsAs,
                    "ShipTo" => [
                        "Name" => $nombreDestinatario . ' ' . $apellidoDestinatario,
                        "AttentionName" => $nombreDestinatario . ' ' . $apellidoDestinatario,
                        "Address" => [
                            "AddressLine" => $direccion,
                            "City" => $ciudad,
                            //"StateProvinceCode" => $ciudad,
                            "PostalCode" => $codigoPostal,
                            "CountryCode" => $codigoPais,
                        ],
                    ],
                    "ShipFrom" => $this->esCPMza($codigoPostal) ? \Arr::except($this->shipperMza,['ShipperNumber','AttentionName']) : \Arr::except($this->shipperBsAs,['ShipperNumber','AttentionName']),
                    "Service" => [
                        "Code" => "65",
                        "Description" => "Saver"
                    ],
                    "Package" => $packages,
                    "PaymentInformation" => [
                        "ShipmentCharge" => [
                            "Type" => "01",
                            "BillShipper" => ["AccountNumber" => "{$upsUserId}"]
                        ],
                    ],
                    "ShipmentRatingOptions" => ["NegotiatedRatesIndicator" => "Y"],
                    "ShipmentTotalWeight" => [
                        "UnitOfMeasurement" => [
                            "Code" => "KGS",
                            "Description" => "KILOS",
                        ],
                        "Weight" => "{$totalWeight}"
                    ],


                ],
            ],
        ];

        $dolarOficial = obtenerDolarUPS();

        $respuesta = [];
        // Get shipment info

        $token = $this->getAccessToken();
        $uri = new Uri($this->config['URL_SHIPMENT']);

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ],json_encode($body,JSON_HEX_QUOT));

        try {
            $response = $this->client->send($request);
            $resultado = json_decode($response->getBody());

        }  catch (\Exception $ex) {
            $response = json_decode($ex->getResponse()->getBody()->getContents(), true);
            throw new \Exception($ex->getMessage(), 1);

            //\Log::channel('consola')->info("UPS - ". $ex->getMessage());
            //dd("SAP - ". $ex->getMessage());
        }


        $respuesta = [
            'tracking_number' => $resultado->ShipmentResponse->ShipmentResults->ShipmentIdentificationNumber,
            //'digest' => $resultado->ShipmentResponse->ShipmentDigest,
            'etiqueta' => $resultado->ShipmentResponse->ShipmentResults->PackageResults[0]->ShippingLabel->GraphicImage,
            'cotizacion_usd' => $dolarOficial,
            'pesos' => ($resultado->ShipmentResponse->ShipmentResults->NegotiatedRateCharges->TotalCharge->MonetaryValue * $dolarOficial)  * 1.21,
            'dolares' => $resultado->ShipmentResponse->ShipmentResults->NegotiatedRateCharges->TotalCharge->MonetaryValue,
        ];


        return $respuesta;

    }

    public function getAccessToken() {
        $uri = new Uri($this->config['URL_AUTH']);

        //$authCredentials = base64_encode($this->config['UPS_CLIENT_ID'].':'.$this->config['UPS_SECRET_ID']);

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic '.base64_encode($this->config['UPS_CLIENT_ID'].':'.$this->config['UPS_SECRET_ID'])
        ]);

        $options = [
            'form_params' => [
                'grant_type' => 'client_credentials'
        ]];

        try {
            $response = $this->client->send($request,$options);
            return json_decode($response->getBody())->access_token;
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - ". $ex->getMessage());
            //dd("SAP - ". $ex->getMessage());
        }

        if($response->getStatusCode() != 200) {
            \Log::channel('consola')->info("SAP - No hubo loggin - ". $response->getStatusCode());
            die();
        }
    }

    protected function esCPMza ($codigoPostal) {
        return in_array($codigoPostal,$this->cpMza);
    }
}
