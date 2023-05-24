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
            dd("SAP - ". $ex->getMessage());
        }

        if($response->getStatusCode() != 200) {
            \Log::channel('consola')->info("SAP - No hubo loggin - ". $response->getStatusCode());
            die();
        }
    }

    public function sincronizarProductos()
    {
        $login = $this->login();

        $param = [
            "\$select" => "ItemCode,ItemName,StockTotal,PriceList,Price,Currency,WhsCode,StockAlmacen"
        ];

        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/sml.svc/VU_ITEMINFO");

        $request = new Psr7\Request('GET', $uri->withQuery(\GuzzleHttp\Psr7\Query::build($param)), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$login->SessionId
        ]);

        try {
            $response = $this->client->send($request);
            $productos = json_decode($response->getBody());
            dd($productos);
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - ". $ex->getMessage());
        }
        \Log::channel('consola')->info("SAP - Conexion OK");
        foreach($productos->value as $producto)
        {
            if (($producto->PriceList == 2 || $producto->PriceList == 1) && $producto->ItemCode != "")
            {
                $aniada = Aniada::where('sku', $producto->ItemCode)->first();

                if ($aniada != null) {
                    \Log::channel('consola')->info($aniada);
                    $aniada->stock = $producto->StockAlmacen;
                    if($producto->PriceList == 2) {
                        $aniada->precio_usd = $producto->Price;
                    } else {
                        $aniada->precio_pesos = $producto->Price * 1.21;
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
            $response = $this->client->send($request);
            \Log::channel('consola')->info('cliente');
            \Log::channel('consola')->info($response->getBody());
            $cliente = json_decode($response->getBody());
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            dd($ex->getResponse()->getBody()->getContents());
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - ". $ex->getMessage());
        }

        return count($cliente->value) > 0 && $cliente->value[0]->CardCode === $codigo;
    }


    public function altaCliente($pedido)
    {
        $login = $this->login();
        \Log::channel('consola')->info("SAP - Alta Cliente");
        $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni);

        if ($this->consultarCliente($codigoCliente))
        {
            return false;
        }

        $cliente["CardCode"] = $codigoCliente;     		//C + NRO DE DOCUMENTO
        $cliente["CardName"] = $pedido->tipo_factura == 'A' ? $pedido->razon_social : $pedido->nombre. ' ' .$pedido->apellido;
        $cliente["CardType"] = "C";
        $cliente["U_B1SYS_FiscIdType"] = $pedido->tipo_factura == 'A' ? 80 : 96;
        $cliente["U_B1SYS_VATCtg"] = $pedido->tipo_factura == 'A' ? "RI" : "CF";
        $cliente["FederalTaxID"] = ($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni);
        $cliente["GroupCode"] = 202;
        $cliente["SalesPersonCode"] = 5;
        $cliente["PriceListNum"] = 2;
        $cliente["EmailAddress"] = $pedido->email;

        $cliente["BPAddresses"] = [];

        $direccion["AddressName"] = $pedido->direccion;
        $direccion["Street"] = $pedido->direccion;
        $direccion["ZipCode"] =  $pedido->cp;
        $direccion["City"] =  $pedido->ciudad;
        $direccion["State"] = "01";			//VER TABLA PROVINCIAS
        $direccion["Country"] = "AR";			//VER TABLA PAISES
        $direccion["TaxCode"] = "IVA_21";
        $direccion["AddressType"] = "bo_BillTo";

        array_push($cliente["BPAddresses"], $direccion);

        $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/BusinessPartners");

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$login->SessionId
        ], json_encode($cliente));

        try {
            $response = $this->client->send($request);
            \Log::channel('consola')->info('alta cliente');
            \Log::channel('consola')->info($response->getBody());
            $cliente = json_decode($response->getBody());
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::channel('consola')->info($ex->getResponse()->getBody()->getContents());
            return false;
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - ". $ex->getMessage());
            return false;
        }
        \Log::channel('consola')->info("SAP - Fin Cliente");
        return isset($cliente->CardCode);
    }

    public function altaVenta($pedido)
    {
        $login = $this->login();

        $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni);
        $this->altaCliente($pedido);
        \Log::channel('consola')->info("SAP - Alta venta");
        $venta["CardCode"] = $codigoCliente;
        $venta["DocDate"] = "DateTime.Now";
        $venta["DocDueDate"] = "DateTime.Now";
        $venta["DocCurrency"] = "ARS";
        $venta["DocType"] = "dDocument_Items";
        $venta["PointOfIssueCode"] = "0047";
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

        $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
            'Content-Type' => 'application/json',
            'Cookie' => 'B1SESSION='.$login->SessionId
        ], json_encode($venta));

        try {
            $response = $this->client->send($request);
            $venta = json_decode($response->getBody());
            \Log::channel('consola')->info('alta pedido');
            $pedido->documento_sap = $venta->DocEntry;
            $pedido->sincronizo_sap = true;
            $pedido->save();
        }  catch (\GuzzleHttp\Exception\RequestException $ex) {
            $error = json_decode($ex->getResponse()->getBody()->getContents());
            $pedido->error_sincronizacion_sap = $error->error->message->value;
            $pedido->save();
            \Log::channel('consola')->info($ex->getResponse()->getBody()->getContents());
        }  catch (\Exception $ex) {
            $pedido->error_sincronizacion_sap = $ex->getMessage();
            $pedido->save();
        }
        \Log::channel('consola')->info("SAP - Fin pedido");
    }

    public function altaPedido($pedido)
    {
        $login = $this->login();

        $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni);
        $this->altaCliente($pedido);
        \Log::channel('consola')->info("SAP - Alta pedido");
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
            \Log::channel('consola')->info($response->getBody());
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
        \Log::channel('consola')->info("SAP - Fin pedido");
    }

    public function sincronizarVentas()
    {
        $pedidosPendientes = Pedido::whereSincronizoSap(false)->where(function($q) {
            $q->whereNull('error_sincronizacion_sap')->orWhere('error_sincronizacion_sap', '');
        })->get();

        \Log::channel('consola')->info("SAP - Ventas");

        if(count($pedidosPendientes) == 0) {
            \Log::channel('consola')->info("SAP - Sin Ventas");
            return false;
        }

        foreach($pedidosPendientes as $pedido)
        {
            $pedido->sincronizo_sap = true;
            $pedido->save();

            try
            {
                if( $pedido->tipo_factura == 'A')
                {
                   $this->altaPedido($pedido);
                } else{
                   $this->altaVenta($pedido);
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
        $pedidosPendientes = Pedido::where('tipo_factura', '<>', 'A')->whereSincronizoPago(false)->where(function($q) {
            $q->whereNull('error_sincronizacion_sap')->orWhere('error_sincronizacion_sap', '');
        })->get();

        \Log::channel('consola')->info("SAP - Pagos");
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
            \Log::channel('consola')->info("SAP - ". $ex->getResponse()->getBody()->getContents());
            dd($ex->getResponse()->getBody()->getContents());
        }  catch (\Exception $ex) {
            \Log::channel('consola')->info("SAP - ". $ex->getMessage());
        }

        $tarjetasArr = [];
        foreach($tarjetas->value as $tarjeta)
        {
            $tarjetasArr[$tarjeta->CardName] = $tarjeta->CreditCard;
        }


        foreach($pedidosPendientes as $pedido)
        {
            $pedido->sincronizo_pago = 1;
            $pedido->save();

            $codigoCliente = "C".($pedido->tipo_factura == 'A' ? $pedido->cuit : $pedido->dni);
            $venta["CardCode"] = $codigoCliente;
            $venta["PaymentInvoices"]["DocEntry"] = $pedido->documento_sap;
            $venta["PaymentCreditCards"]["CreditCard"] = array_key_exists($pedido->tipo_tarjeta, $tarjetasArr) ? $tarjetasArr[$pedido->tipo_tarjeta] : 2;
            $venta["PaymentCreditCards"]["CreditCardNumber"] = $pedido->tarjeta;
            $venta["PaymentCreditCards"]["CardValidUntil"] = $pedido->tarjeta_exp;
            $venta["VoucherNum"]["CardValidUntil"] = $pedido->numero_voucher;
            $venta["VoucherNum"]["CreditSum"] = $pedido->total_envio;

            $uri = new Uri("https://{$this->host}:{$this->port}/b1s/v1/IncomingPayments");

            $request = new Psr7\Request('POST', $uri->withQuery(\GuzzleHttp\Psr7\Query::build([])), [
                'Content-Type' => 'application/json',
                'Cookie' => 'B1SESSION='.$login->SessionId
            ], json_encode($venta));

            try {
                $response = $this->client->send($request);
                $venta = json_decode($response->getBody());

                $pedido->sincronizo_pago = 1;
                $pedido->save();
            }  catch (\GuzzleHttp\Exception\RequestException $ex) {
                $error = json_decode($ex->getResponse()->getBody()->getContents());
                $pedido->error_sincronizacion_sap = $error->error->message->value;
                $pedido->save();
            }  catch (\Exception $ex) {
                $pedido->error_sincronizacion_sap = $ex->getMessage();
                $pedido->save();
            }

            \Log::channel('consola')->info("SAP - Fin Pagos");
        }
    }
}
