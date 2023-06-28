<?php

namespace App\Http\Controllers\Front;

use App\Pais;
//use App\Http\Requests\Front\CheckoutPagarRequest;
//use App\Repositories\PedidosRepository;
//use App\Services\ConfiguracionesService;
//use App\Services\EnvioService;
use App\Aniada;
use App\RegistradoDireccion;
use App\Services\UPSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Repositories\PedidoRepository;
use App\Http\Controllers\AppBaseController;
//use Srmklive\PayPal\Services\ExpressCheckout;

class CheckoutController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function index($lang)
    {
        //$precio = precioLibroPorPais();

        $itemsCarrito = \Cart::getContent();

        $this->data['checkout'] = [
            'secciones' => [
                'datosContacto' => true,
                'envioRetiro' => false,
                'datosDestinatario' => false,
                'datosFacturacion' => false,
                'comentarios' => false
            ],
            'seccionActual' => 'datosContacto',

            'form' => [
                'registrado_id' => auth()->user()->id,
                'email' => auth()->user()->email,
                'recibir' => auth()->user()->newsletter()->count() > 0,
                'envio_retiro' => 'E',
                'envio_retiro_id' => null,
                'tipo_factura' => null,
                'tipo_factura_desc' => 'Datos de facturación',
                'total_envio' => 0,
                'total_envio_usd' => 0,
                'cotizacion_usd' => 0,
                'total' => 0,
                'total_usd' => 0,
                'comentarios' => null,
                'pais_id_fc' => 3,
            ],
            'info' => [
                'paises' => Pais::whereEnabled(true)->orderBy('nombre')->select('id','codigo','nombre')->get(),
                'montoDatosFC' => config('constantes.montoDatosFC')
            ],
            'modelos' => [
                'direccion' => [
                    'id' => 0,
                    'registrado_id' => auth()->user()->id,
                    'nombre' => null,
                    'apellido' => null,
                    'calle' => null,
                    'ciudad' => null,
                    'cp' => null,
                    'provincia' => null,
                    'pais_id' => 3,
                    'pais' => null,
                    'principal' => true
                ],
            ],
            'direcciones' => [
                'itemSeleccionado' => null,
                'listado' => auth()->user()->direcciones()->with('pais')->orderBy('principal','desc')->get(),
                'url_guardar' => routeIdioma('miCuenta.direcciones.guardar'),
                'enviando' => false
            ],
            'url_cotizar_envio' => routeIdioma('checkout.cotizarEnvio'),
            'url_confirmar' => routeIdioma('checkout.confirmar'),
            'enviando' => false,
            'cotizando_envio' => false,
            'confirmando' => false
            /*'listado' => [
                'items' => $itemsCarrito,

            ],
            'url_quitar' => routeIdioma('carrito.quitar', ['_ID_']),
            'url_checkout' => routeIdioma('carrito'),
            'total' => \Cart::total(),
            'cantidad' => \Cart::count(),
            'agregando' => false,*/

        ];

        return view('front.checkout', [
            'tituloPagina' => trans('front.paginas.checkout.titulo'),
            'dataSection' => 'checkout',
            'data' => $this->data
        ]);
    }

    public function gracias($lang,$guid,Request $request, PedidoRepository $pedidosRepo) {
        $pedido = $pedidosRepo->scopeQuery(function($q) use($guid){
            return $q->where(DB::raw('md5(id)'),$guid)
                    ->whereRegistradoId(auth()->user()->id);
        })->first();

        if (!$pedido || \Cart::getContent()->count() < 1) {
            return redirect()->to(routeIdioma('home'));
        }

        \Cart::clear();


        $this->data['checkout'] = [
            'mensaje' => trans('front.paginas.checkout.gracias.mensajeTipoFactura.'.$pedido->tipo_factura),
            'pedido' => $pedido
        ];
        return view('front.checkout-gracias', [
            'tituloPagina' => trans('front.paginas.checkout.gracias.mensajeTipoFactura.'.$pedido->tipo_factura.'.titulo'),
            'dataSection' => 'checkout',
            'data' => $this->data
        ]);
    }

    public function cotizarEnvio($lang, Request $request, UPSService $upsService) {
        try {
            $salida = [];

            $montoEnvioGratis = config('constantes.montoEnvioGratis',false);
            if ($montoEnvioGratis && \Cart::getTotal() >= $montoEnvioGratis) {
                return $this->sendResponse([
                    'cotizacion' => 0,
                    'dolares' => 0,
                    'pesos' => 0
                ], trans('admin.success'));;
            }

            $itemsCarrito = \Cart::getContent();
            $ids = [];
            $productos = [];
            $total = 0;
            foreach ($itemsCarrito as $item) {
                $ids[] = $item->attributes->aniada_id;
                for($x=0; $x < $item->quantity; $x++) {
                    $producto = Aniada::with('vino')->where('id',$item->attributes->aniada_id)->first();
                    array_push($productos, $producto);
                }
                $total += $item->quantity;
            }

            //logger($request->all());
            //logger([$request->pais->codigo, $request->cp]);

            $pais = Pais::find($request->pais_id);
            $respuesta = $upsService->cotizarEnvio($pais->codigo, $request->cp, $request->calle, $request->ciudad, $productos);
            $salida = $respuesta;

            return $this->sendResponse($salida, trans('admin.success'));
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }


    public function confirmar($lang, Request $request, PedidoRepository $pedidosRepo)
    {

        try {
            if (!auth()->check()) {
                throw new \Exception('Debes iniciar sesion');
            }


            $items = [];
            $totalCarrito = 0;
            $totalCarritoUsd = 0;
            foreach (\Cart::getContent() as $cartItem) {
                $attributes = $cartItem->attributes->toArray();
                //\Log::info($attributes);
                $item = [
                    'aniada_id' => $attributes['aniada_id'],
                    'precio_pesos' => $attributes['precio_pesos'],
                    'precio_usd' => $attributes['precio_usd'],
                    'cantidad' => $cartItem->quantity,
                ];
                $items[] = $item;
                $totalCarrito += (float)$attributes['precio_pesos'] * $cartItem->quantity;
                $totalCarritoUsd += (float)$attributes['precio_usd'] * $cartItem->quantity;
            }
            $totalEnvio = (float)$request->total_envio;
            $totalEnvioUsd = (float)$request->total_envio_usd;
            $total = $totalCarrito + $totalEnvio;
            $totalUsd = $totalCarritoUsd + $totalEnvioUsd;

            $direccionEnvio = RegistradoDireccion::find($request->envio_retiro_id);

            if (!$direccionEnvio) {
                throw new \Exception('No existe la dirección seleccionada');
            }

            //throw new \Exception('Debes iniciar sesion');
            $dataPedido = array_merge([], [
                'registrado_id' => auth()->user()->id,
                'estado_id' => 0,
                'total_carrito' => $totalCarrito,
                'total_carrito_usd' => $totalCarritoUsd,
                'total_envio' => $totalEnvio,
                'total_envio_usd' => $totalEnvioUsd,
                'total' => $total,
                'total_usd' => $totalUsd,
                'direccion' => $direccionEnvio->calle,
                'ciudad' => $direccionEnvio->ciudad,
                'provincia' => $direccionEnvio->provincia,
                'pais_id' => $direccionEnvio->pais_id,
                'cp' => $direccionEnvio->cp,
                //'email' => $request->email,
                //'tipo_factura' => 'CF',
                //'nombre' => 'Lucas',
                //'apellido' => 'Grzina',
                //'dni' => '30007516'
            ],$request->only([
                'email',
                'tipo_factura',
                'nombre',
                'apellido',
                'dni',
                'razon_social',
                'cuit',
                'envio_retiro',
                'cotizacion_usd',
                'comentarios',
                'nombre_fc',
                'apellido_fc',
                'dni_fc',
                'direccion_fc',
                'ciudad_fc',
                'cp_fc',
                'provincia_fc',
                'pais_id_fc',
            ]));

            $pedido = $pedidosRepo->altaDesdeCarrito($dataPedido, $items);

            if (\Auth::user()->email === env('EMAIL_PEDIDO_PRUEBA','')) {
                //$pedido->ult_estado_pago = 'aprobado';

                $pedido->estado_id = 1;
                $pedido->save();
                $pedido->registrado->enviarNotificacionPedido($pedido);
                //throw new \Exception("Error Processing Request", 1);

                $salida = ['redirect' => routeIdioma('home')];
                \Cart::clear();
            } else {
                try {
                    $pedido->registrado->enviarNotificacionPedido($pedido);
                } catch (\Exception $e) {
                    logger('Checkout::confirmar: '.$e->getMessage());
                }
                $salida = $this->armarPreferenciaPago($pedido);
            }

            return $this->sendResponse($salida, trans('admin.success'));

        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }



    }
    /*
    public function checkoutPagar(CheckoutPagarRequest $request, PedidosRepository $pedidosRepo, EnvioService $srvEnvio)
    {
        //\Log::info(codigoPaisUsuario());
        //return $this->sendResponse($request->all(), trans('admin.success'));

        try {
            $configuraciones = configuracionesPorPais();
            $precio = $configuraciones['precio'];
            $items = [];
            $totalCarrito = 0;
            foreach (\Cart::getContent() as $cartItem) {
                $attributes = $cartItem->attributes->toArray();
                //\Log::info($attributes);
                $item = [
                    'identificador' => $attributes['identificador'],
                    'nombre' => $attributes['form']['nombre'],
                    'genero' => $attributes['form']['genero'],
                    'personaje' => $attributes['form']['personaje'],
                    'idioma' => $attributes['form']['idioma'],
                    'dedicatoria' => $attributes['form']['dedicatoria'],
                    'foto' => $attributes['form']['foto'],
                    'precio' => $precio,
                    'cuento' => $attributes['cuento']
                ];
                $items[] = $item;
                $totalCarrito += $precio;
            }

            $totalEnvio = $srvEnvio->calcular($request->cp, $request->provincia_id, $request->pais_id);

            $dataPedido = array_merge($request->except(['acepta_tc']), [
                'registrado_id' => \Auth::user()->id,
                'estado_id' => 0,
                'total_carrito' => $totalCarrito,
                'total_envio' => $totalEnvio,
                'total' => $totalCarrito + $totalEnvio,
                'plataforma_pago' => codigoPaisUsuario() === 'ar' ? 'MP' : 'Paypal',
                'moneda' => $configuraciones['moneda']
            ]);

            $pedido = $pedidosRepo->altaDesdeCarrito($dataPedido, $items);

            if (\Auth::user()->email === env('EMAIL_PEDIDO_PRUEBA','')) {
                $pedido->ult_estado_pago = 'aprobado';
                $pedido->estado_id = 1;
                $pedido->save();
                $pedido->registrado->enviarNotificacionPedidoConfirmado($pedido);
                $salida = ['mp_redirect' => route('home')];
            } else {
                $salida = $this->armarPreferenciaPago($pedido);
            }



            try {
                //Mail::send(new NuevoPedidoMail($pedido));
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }

            //\Cart::destroy();
            return $this->sendResponse($salida, trans('admin.success'));
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(), 500);
        }
    }
    */
    protected function armarPreferenciaPago($pedido)
    {
        return [
            'redirect' => routeIdioma('checkout.gracias',[md5($pedido->id)])
        ];
        /*
        if ($pedido->plataforma_pago === 'MP') {
            $preferenceData = [
                'items' => [
                    [
                        'title' => 'Pedido "buscandominombre" Nro ' . $pedido->id,
                        'description' => 'Pedido "buscandominombre" Nro ' . $pedido->id,
                        'quantity' => 1,
                        'currency_id' => 'ARS',
                        'unit_price' => $pedido->total,
                    ]
                ],
                'notification_url' => str_replace('_PEDIDO_ID_', md5($pedido->id), config('mercadopago.notification_url', '')),
                'back_urls' => [
                    'success' => route('mp.actualizarPagoPedido', [md5($pedido->id), 'success']),
                    'failure' => route('mp.actualizarPagoPedido', [md5($pedido->id), 'failure']),
                    'pending' => route('mp.actualizarPagoPedido', [md5($pedido->id), 'pending']),
                ],
                'external_reference' => $pedido->id,
            ];

            $preference = \MP::create_preference($preferenceData);

            if ($preference['status'] == 200 || $preference['status'] == 201) {
                $salida = [
                    'pedido' => $pedido,
                    'mp_redirect' => config('mercadopago.app_sandbox') ? $preference['response']['sandbox_init_point'] : $preference['response']['init_point']
                ];
            } else {
                throw new \Exception('Error al generar el pago en PM', 1);
            }
        } else {
            $data = [];
            $data['items'] = [
                [
                    'name' => 'Pedido "buscandominombre" Nro ' . $pedido->id,
                    'price' => $pedido->total,
                    'desc' => 'Pedido "buscandominombre" Nro ' . $pedido->id,
                    'quantity' => 1
                ]
            ];

            $data['invoice_id'] = $pedido->id;
            $data['invoice_description'] = "Pedido #{$data['invoice_id']}";
            $data['return_url'] = route('paypal.actualizarPagoPedido', [md5($pedido->id), 'success']);
            $data['cancel_url'] = route('paypal.actualizarPagoPedido', [md5($pedido->id), 'failure']);
            $data['notify_url'] = str_replace('_PEDIDO_ID_', md5($pedido->id), config('paypal.notify_url', ''));
            $data['total'] = $pedido->total;

            $provider = new ExpressCheckout;

            $preference = $provider->setExpressCheckout($data);

            if ($preference['ACK'] == 'Success') {
                $salida = [
                    'pedido' => $pedido,
                    'mp_redirect' => $preference['paypal_link']
                ];
            } else {
                throw new \Exception('Error al generar el pago en Paypal', 1);
            }
        }

        return $salida;
        */
    }
}
