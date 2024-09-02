<?php
namespace App\Services;

use App\Aniada;
use App\Http\Controllers\AppBaseController;
use App\Pedido;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Uri;

class SAPService extends AppBaseController
{
    protected $repository;
    protected $client;
    protected $host;
    protected $port;

    public function __construct()
    {
        $this->host = env("SAP_HOST");
        $this->port = env("SAP_POST");
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
    }

    public function login()
    {
        // Login credentials
        $body = [
            "UserName" => env("SAP_USERNAME"),
            "Password" => env("SAP_PASSWORD"),
            "CompanyDB" => env("SAP_COMPANY"),
        ];

        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/Login");

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/json'
        ], json_encode($body));

        try {
            $response = $this->client->send($request);
            return json_decode($response->getBody());
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - ". $ex->getMessage());
            //dd("SAP - ". $ex->getMessage());
        }

        if($response->getStatusCode() != 200) {
            \Log::channel('consola')->info("SAP - No hubo loggin - ". $response->getStatusCode());
            die();
        }
    }

    public function sincronizarProductos()
    {
        $login = $this->login();
        \Log::channel('consola')->info("SAP - Productos");

        $codigosAniadas = Aniada::whereNotNull('sku')->pluck('sku')->toArray();
        //\Log::channel('consola')->info($codigosAniadas);
        $filtersItemCode = [];
        foreach ($codigosAniadas as $sku) {
            $filtersItemCode[] = "ItemCode eq '{$sku}'";
        }


        $productos = [];

        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/sml.svc/VU_ITEMINFO");

        try {
            $i = 0;
            do {
                $param = [
                    "\$select" => "ItemCode,ItemName,StockTotal,PriceList,Price,Currency,WhsCode,StockAlmacen",
                    "\$filter" => "(" . implode(' or ',$filtersItemCode) . ") and (PriceList eq 2 or PriceList eq 3)",
                    "\$skip" => $i * 20
                ];
                //\Log::channel('consola')->info($param);
                //\Log::channel('consola')->info('Url API: '."https://{$this->host}:{$this->port}/b1s/v1/sml.svc/VU_ITEMINFO");



                $request = new Psr7\Request('GET', $uri->withQuery(\GuzzleHttp\Psr7\Query::build($param)), [
                    'Content-Type' => 'application/json',
                    'Cookie' => 'B1SESSION='.$login->SessionId
                ]);


                $response = $this->client->send($request);
                //$productos = json_decode($response->getBody());
                $response = json_decode($response->getBody());
                $arrResp = (array)$response;
                //\Log::channel('consola')->info($arrResp);

                foreach($response->value as $producto) {
                    $productos[] = $producto;
                }

                $hayMas = isset($arrResp['@odata.nextLink']);

                $i++;
                if ($i > 5) {
                    $hayMas = false;
                }
            } while ($hayMas);


        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - ". $ex->getMessage());
        }
        //\Log::channel('consola')->info(json_encode($productos->value));
        foreach($productos as $producto)
        {

            if (($producto->PriceList == 2 || $producto->PriceList == 3) && $producto->ItemCode != "")
            {

                $aniada = Aniada::where('sku', $producto->ItemCode)->first();

                if ($aniada != null) {

                    $aniada->stock = $producto->StockAlmacen;
                    if($producto->PriceList == 3) {
                        $aniada->precio_usd = $producto->Price;
                    }
                    if($producto->PriceList == 2) {
                        $aniada->precio_pesos = $producto->Price;
                    }

                    $aniada->save();


                }
            }
        }
        \Log::channel('consola')->info("SAP - FIN");
    }

    public function consultarCliente($codigo)
    {
        $login = $this->login();

        $param["\$filter"] = "(CardCode eq '{$codigo}')";
        $param["\$select"] = "CardCode,CardName,FederalTaxID,EmailAddress,BPAddresses";

        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/BusinessPartners");

        $request = new Psr7\Request('GET', $uri->withQuery(\GuzzleHttp\Psr7\Query::build($param)), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$login->SessionId
        ]);

        try {
            //\Log::channel('consola')->info('SAP - consultarCliente: ' . json_encode($param));
            $response = $this->client->send($request);
            //\Log::channel('consola')->info('SAP - consultarCliente: Resp '. $response->getBody());
            $cliente = json_decode($response->getBody());
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::channel('consola')->info("SAP - consultarCliente". $ex->getResponse()->getBody()->getContents());
            dd($ex->getResponse()->getBody()->getContents());
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - consultarCliente". $ex->getMessage());
        }

        return count($cliente->value) > 0 && $cliente->value[0]->CardCode == $codigo;
    }


    public function altaCliente($pedido)
    {
        $login = $this->login();
        //\Log::channel('consola')->info("SAP - Alta Cliente");
        $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni_fc);

        $cliente["CardCode"] = $codigoCliente;     		//C + NRO DE DOCUMENTO
        $cliente["CardName"] = $pedido->tipo_factura == 'A' ? $pedido->razon_social : $pedido->nombre_fc. ' ' .$pedido->apellido_fc;
        $cliente["CardType"] = "C";
        $cliente["U_B1SYS_FiscIdType"] = $pedido->tipo_factura == 'A' ? 80 : 96;
        $cliente["U_B1SYS_VATCtg"] = $pedido->tipo_factura == 'A' ? "RI" : "CF";
        $cliente["FederalTaxID"] = ($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni_fc);
        $cliente["GroupCode"] = 102;
        $cliente["SalesPersonCode"] = 3;
        $cliente["PriceListNum"] = 2;
        $cliente["EmailAddress"] = $pedido->email;

        $cliente["BPAddresses"] = [];

        $direccion["AddressName"] = "Domicilio Declarado";
        $direccion["Street"] = $pedido->direccion_fc;
        $direccion["ZipCode"] =  $pedido->cp_fc;
        $direccion["City"] =  $pedido->ciudad_fc;
        $direccion["State"] = $this->obtenerProvinciaSAP($pedido->provincia_fc);			//VER TABLA PROVINCIAS
        $direccion["Country"] = "AR";			//VER TABLA PAISES
        $direccion["TaxCode"] = "IVA_21";
        $direccion["AddressType"] = "bo_BillTo";

        array_push($cliente["BPAddresses"], $direccion);

        $direccion["AddressName"] = "Domicilio Declarado Envio";
        $direccion["Street"] = $pedido->direccion;
        $direccion["ZipCode"] =  $pedido->cp;
        $direccion["City"] =  $pedido->ciudad;
        $direccion["State"] = $this->obtenerProvinciaSAP($pedido->provincia);			//VER TABLA PROVINCIAS
        $direccion["Country"] = "AR";			//VER TABLA PAISES
        $direccion["TaxCode"] = "IVA_21";
        $direccion["AddressType"] = "bo_ShipTo";

        array_push($cliente["BPAddresses"], $direccion);

        if ($this->consultarCliente($codigoCliente))
        {
            $rowNum = 0;
            foreach($cliente["BPAddresses"] as &$direccion )
            {
                $direccion["RowNum"] = $rowNum;
                $direccion["BPCode"] = $codigoCliente;
                $rowNum++;
            }

            $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/BusinessPartners('{$codigoCliente}')");
            \Log::channel('consola')->info('SAP - patchCliente: ' . "https://{$this->host}:{$this->port}/b1s/v1/BusinessPartners('{$codigoCliente}')");
            \Log::channel('consola')->info('SAP - patchCliente: ' . json_encode($cliente));
            $request = new Psr7\Request('PATCH', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
                'Content-Type' => 'application/json',
                'Cookie' => 'B1SESSION='.$login->SessionId
            ], json_encode($cliente));

        } else {
            $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/BusinessPartners");
            \Log::channel('consola')->info('SAP - postCliente: ' . "https://{$this->host}:{$this->port}/b1s/v1/BusinessPartners");
            \Log::channel('consola')->info('SAP - postCliente: ' . json_encode($cliente));

            $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
                'Content-Type' => 'application/json',
                'Cookie' => 'B1SESSION='.$login->SessionId
            ], json_encode($cliente));
        }

        try {

            $response = $this->client->send($request);
            $cliente = json_decode($response->getBody());
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::channel('consola')->info('SAP - respCliente: ' . $ex->getResponse()->getBody()->getContents());
            return false;
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - respCliente: ". $ex->getMessage());
            return false;
        }
        //\Log::channel('consola')->info("SAP - Fin Cliente");
        return isset($cliente->CardCode);
    }

    public function altaVenta($pedido)
    {
        \Log::channel('consola')->info('altaVenta pedido ' . $pedido->id);
        $login = $this->login();

        $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni_fc);
        $this->altaCliente($pedido);
       // \Log::channel('consola')->info("SAP - Alta venta");
        $venta["CardCode"] = $codigoCliente;
        $venta["DocDate"] = "DateTime.Now";
        $venta["DocDueDate"] = "DateTime.Now";
        $venta["DocCurrency"] = "ARS";
        $venta["DocType"] = "dDocument_Items";
        $venta["PointOfIssueCode"] = env('SAP_POINT_ISSUE_CODE');
        $venta["Letter"] = "fLetterB";
        $venta["Comments"] = "Documento generado por e-commerce";

        $itemsVenta =[];
        foreach($pedido->items as $item)
        {
            $itemVenta = [];
            $itemVenta["ItemCode"] = $item->aniada->sku;
            $itemVenta["Quantity"] = $item->cantidad;
            $itemVenta["TaxCode"] = "IVA_21";
            $itemVenta["UnitPrice"] = $item->precio_pesos / 1.21;
            $itemVenta["WarehouseCode"] = "MDUV";

            array_push($itemsVenta, $itemVenta);
        }

        if ($pedido->total_envio > 0)
        {
            $itemVenta = [];
            $itemVenta["ItemCode"] = 'MZCENVIO';
            $itemVenta["Quantity"] = 1;
            $itemVenta["TaxCode"] = "IVA_21";
            $itemVenta["UnitPrice"] = $pedido->total_envio / 1.21;
            $itemVenta["WarehouseCode"] = "MDUV";

            array_push($itemsVenta, $itemVenta);
        }

        $venta["DocumentLines"] = $itemsVenta;

        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/Invoices");

        /*Log::channel('consola')->info([
            'Command' => 'altaVenta',
            'url' => "https://{$this->host}:{$this->port}/b1s/v1/Invoices",
            'Cookie' => 'B1SESSION='.$login->SessionId,
            'data' => json_encode($venta)
        ]);*/

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$login->SessionId
        ], json_encode($venta));

        \Log::channel('consola')->info('altaVenta request: ' . json_encode($venta));

        try {
            $response = $this->client->send($request);

            $venta = json_decode($response->getBody());
            \Log::channel('consola')->info('altaVenta response: ' . json_encode($venta));
            $pedido->documento_sap = $venta->DocEntry;
            $pedido->sincronizo_sap = true;
            $pedido->save();
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            $error = json_decode($ex->getResponse()->getBody()->getContents());
            \Log::channel('consola')->error('altaVenta error: ' . json_encode($error));
            $pedido->error_sincronizacion_sap = $error->error->message->value;
            $pedido->save();
        }  catch (\Exception $ex) {
            \Log::channel('consola')->error('altaVenta error: ' . $ex->getMessage());
            $pedido->error_sincronizacion_sap = $ex->getMessage();
            $pedido->save();
        }
       // \Log::channel('consola')->info("SAP - Fin pedido");
    }

    public function altaPedido($pedido)
    {
        $login = $this->login();

        $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni_fc);
        $this->altaCliente($pedido);
        //\Log::channel('consola')->info("SAP - Alta pedido");
        $venta["CardCode"] = $codigoCliente;
        $venta["DocDueDate"] = Carbon::now()->format('Y-m-d');
        $venta["DocCurrency"] = "ARS";
        $venta["DocType"] = "dDocument_Items";
        $venta["Comments"] = "Documento generado por e-commerce";

        $itemsVenta =[];
        foreach($pedido->items as $item)
        {
            $itemVenta = [];
            $itemVenta["ItemCode"] = $item->aniada->sku;
            $itemVenta["Quantity"] = $item->cantidad;
            $itemVenta["TaxCode"] = "IVA_21";
            $itemVenta["UnitPrice"] = $item->precio_pesos / 1.21;
            $itemVenta["WarehouseCode"] = "MDUV";

            array_push($itemsVenta, $itemVenta);
        }

        if ($pedido->total_envio > 0)
        {
            $itemVenta = [];
            $itemVenta["ItemCode"] = 'MZCENVIO';
            $itemVenta["Quantity"] = 1;
            $itemVenta["TaxCode"] = "IVA_21";
            $itemVenta["UnitPrice"] = $pedido->total_envio / 1.21;
            $itemVenta["WarehouseCode"] = "MDUV";

            array_push($itemsVenta, $itemVenta);
        }

        $venta["DocumentLines"] = $itemsVenta;
        //logger("https://{$this->host}:{$this->port}/b1s/v1/Orders");
        //logger(json_encode($venta));
        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/Orders");

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$login->SessionId
        ], json_encode($venta));

        try {
            $response = $this->client->send($request);
            $venta = json_decode($response->getBody());
            //dd($venta);

            $pedido->documento_sap = $venta->DocEntry;
            $pedido->sincronizo_sap = true;
            $pedido->sincronizo_pago = true;
            $pedido->save();
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            $error = json_decode($ex->getResponse()->getBody()->getContents());
            //dd($error);
            $pedido->error_sincronizacion_sap = $error->error->message->value;
            $pedido->save();
            \Log::channel('consola')->info($ex->getResponse()->getBody()->getContents());
        }  catch (\Exception $ex) {
            //dd($ex);
            $pedido->error_sincronizacion_sap = $ex->getMessage();
            $pedido->save();
        }
        //\Log::channel('consola')->info("SAP - Fin pedido");
    }

    public function sincronizarVentas()
    {
        $pedidosPendientes = Pedido::whereSincronizoSap(false)->where(function($q) {
            $q->where(function($q) {
                $q->whereTipoFactura("CF")->wherePpStatus("aprobado");
            })->orWhere("tipo_factura","A");
        })->get();

        \Log::channel('consola')->info($pedidosPendientes->toArray());

        if(count($pedidosPendientes) == 0) {
        //    \Log::channel('consola')->info("SAP - Sin Ventas");
            $this->sincronizarPagos();
            return false;
        }

        foreach($pedidosPendientes as $pedido)
        {
            //$pedido->sincronizo_sap = true;
            //$pedido->save();
            try
            {
                if( $pedido->tipo_factura == 'A')
                {
                   $this->altaPedido($pedido);
                } else{
                    if($pedido->pp_status == 'aprobado') {
                        $this->altaVenta($pedido);
                    }
                }

            } catch (\Exception $ex) {
                $pedido->error_sincronizacion_sap = $ex->getMessage();
                $pedido->save();
            }
        }

        $this->sincronizarPagos();
    }

    public function sincronizarPagos()
    {
        $pedidosPendientes = Pedido::where('tipo_factura', '<>', 'A')->where('pp_status', 'aprobado')->whereSincronizoSap(true)->whereSincronizoPago(false)->get();
        $login = $this->login();

        if(count($pedidosPendientes) == 0) {
            return false;
        }

        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/SQLQueries('ConsultarTC')/List");
        $request = new Psr7\Request('GET', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$login->SessionId
        ]);
        try {
            $response = $this->client->send($request);
            $tarjetas = json_decode($response->getBody());
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::channel('consola')->error("SAP - sincronizarPagos - ConsultarTC: ". $ex->getResponse()->getBody()->getContents());
            //dd($ex->getResponse()->getBody()->getContents());
        }  catch (\Exception $ex) {
            \Log::channel('consola')->error("SAP - sincronizarPagos - ConsultarTC: ". $ex->getMessage());
        }

        $tarjetasArr = [];
        foreach($tarjetas->value as $tarjeta)
        {
            $tarjetasArr[$tarjeta->CardName] = $tarjeta->CreditCard;
        }


            foreach($pedidosPendientes as $pedido)
            {
                try {
                    $codigo = 0;

                    if($pedido->tipo_tarjeta == 'account_money') {
                        $codigo = 15;
                    }

                    if($pedido->tipo_tarjeta == 'amex') {
                        $codigo = 14;
                    }

                    if($pedido->tipo_tarjeta == 'master') {
                        $codigo = 3;
                    }
                    if( $codigo == 0 ) {
                        $codigo = array_key_exists(strtoupper($pedido->tipo_tarjeta), $tarjetasArr) ? $tarjetasArr[strtoupper($pedido->tipo_tarjeta)] : 2;
                    }

                    \Log::channel('consola')->info("SAP - sincronizarPagos - Pedido Fecha exp tarj: ". $pedido->tarjeta_exp);

                    $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni_fc);
                    $venta["CardCode"] = $codigoCliente;
                    $venta["PaymentInvoices"] = [];
                    array_push($venta["PaymentInvoices"], ["DocEntry" => $pedido->documento_sap]);
                    $credict = [];
                    $credict["CreditCard"] = $codigo;
                    $credict["CreditCardNumber"] = $pedido->tarjeta;
                    $credict["CardValidUntil"] = $pedido->tarjeta_exp ? Carbon::parse('1/'.$pedido->tarjeta_exp)->endOfMonth()->format('Y-m-d') : null;
                    $credict["VoucherNum"] = (string) $pedido->documento_sap;
                    $credict["CreditSum"] = (float)$pedido->total;
                    $credict["NumOfPayments"] = $pedido->tarjeta_cuotas;
                    $credict["CreditCur"] =  "ARS";
                    $credict["NumOfCreditPayments"] = 1;
                    $credict["CreditType"] =  "cr_InternetTransaction";
                    $credict["SplitPayments"] =  "tYES";
                    $credict["PaymentMethodCode"] =  1;
                    $venta["PaymentCreditCards"]= [];
                    array_push($venta["PaymentCreditCards"], $credict);


                    \Log::channel('consola')->info("SAP - sincronizarPagos - Pedido ID: ". $pedido->id);
                    \Log::channel('consola')->info("SAP - sincronizarPagos - IncomingPayments: ". json_encode($venta));
                    $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/IncomingPayments");

                    $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
                        'Content-Type' => 'application/json',
                        'Cookie' => 'B1SESSION='.$login->SessionId
                    ], json_encode($venta));

                    try {
                        $response = $this->client->send($request);
                        $venta = json_decode($response->getBody());
                        \Log::channel('consola')->info("SAP - sincronizarPagos - Response: ". json_encode($venta));
                        $pedido->error_sincronizacion_sap = "";
                        $pedido->sincronizo_pago = 1;
                        $pedido->save();
                    }  catch (\GuzzleHttp\Exception\RequestException $ex) {
                        $error = json_decode($ex->getResponse()->getBody()->getContents());
                        \Log::channel('consola')->error("SAP - sincronizarPagos: ". $error->error->message->value);
                        $pedido->error_sincronizacion_sap = $pedido->error_sincronizacion_sap . "||" . $error->error->message->value;
                        $pedido->save();
                    }  catch (\Exception $ex) {
                        \Log::channel('consola')->error("SAP - sincronizarPagos: ". $ex->getMessage());
                        $pedido->error_sincronizacion_sap = $pedido->error_sincronizacion_sap . "||" . $ex->getMessage();
                        $pedido->save();
                    }
                } catch (\Exception $ex) {
                    \Log::channel('consola')->error("SAP - sincronizarPagos - Al generar pago para subir: ". $ex->getMessage());
                    $pedido->error_sincronizacion_sap = $pedido->error_sincronizacion_sap . "||" . $ex->getMessage();
                    $pedido->save();
                }
                //\Log::channel('consola')->info("SAP - Fin Pagos");
            }

    }

    public function obtenerProvinciaSAP($provincia) {
        $provincia = strtolower($provincia);
        if(strpos($provincia, 'autono') > 0 || strpos($provincia, 'caba') > 0 || strpos($provincia, 'federal') > 0) {
            return '00';
        }
        if(strpos($provincia, 'bs') > 0 || strpos($provincia, 'buenos') > 0 || strpos($provincia, 'aires') > 0 ) {
            return '01';
        }
        if(strpos($provincia, 'cata') > 0 || strpos($provincia, 'marca') > 0) {
            return '02';
        }
        if(strpos($provincia, 'cordoba') > 0 || strpos($provincia, 'córdo') > 0 || strpos($provincia, 'rdoba') > 0) {
            return '03';
        }
        if(strpos($provincia, 'corr') > 0 || strpos($provincia, 'rrientes') > 0) {
            return '04';
        }
        if(strpos($provincia, 'entre') > 0) {
            return '05';
        }
        if(strpos($provincia, 'juj') > 0) {
            return '06';
        }
        if(strpos($provincia, 'mendo') > 0 || strpos($provincia, 'mza') > 0) {
            return '07';
        }
        if(strpos($provincia, 'rioja') > 0) {
            return '08';
        }
        if(strpos($provincia, 'salta') > 0) {
            return '09';
        }
        if(strpos($provincia, 'juan') > 0) {
            return '10';
        }
        if(strpos($provincia, 'luis') > 0) {
            return '11';
        }
        if(strpos($provincia, 'cruz') > 0) {
            return '12';
        }
        if(strpos($provincia, 'fe') > 0) {
            return '13';
        }
        if(strpos($provincia, 'santiago') > 0 || strpos($provincia, 'este') > 0) {
            return '14';
        }
        if(strpos($provincia, 'tucu') > 0) {
            return '15';
        }
        if(strpos($provincia, 'chaco') > 0  || strpos($provincia, 'chco') > 0) {
            return '16';
        }
        if(strpos($provincia, 'chu') > 0) {
            return '17';
        }
        if(strpos($provincia, 'form') > 0) {
            return '18';
        }
        if(strpos($provincia, 'misi') > 0) {
            return '19';
        }
        if(strpos($provincia, 'neuq') > 0) {
            return '20';
        }
        if(strpos($provincia, 'pampa') > 0) {
            return '21';
        }
        if(strpos($provincia, 'negr') > 0) {
            return '22';
        }
        if(strpos($provincia, 'fuego') > 0 || strpos($provincia, 'tierra') > 0 || strpos($provincia, 'tdf') > 0) {
            return '24';
        }

        return '99';
    }
}
