<?php

namespace App\Http\Controllers\Front;

use App\Aniada;
//use App\Http\Requests\Front\CheckoutPagarRequest;
//use App\Repositories\PedidosRepository;
//use App\Services\ConfiguracionesService;
//use App\Services\EnvioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AppBaseController;
//use Srmklive\PayPal\Services\ExpressCheckout;

class CarritoController extends AppBaseController
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
        $itemsCarrito = \Cart::getContent();

        $this->data['detalleCarrito'] = [
            'info' => [
            ],
        ];

        return view('front.carrito', [
            'tituloPagina' => trans('front.paginas.carrito.titulo'),
            'dataSection' => 'mis-pedidos',
            'data' => $this->data
        ]);
    }

    public function quitar($lang, $id, Request $request)
    {
        \Cart::remove($id);

        $data = [
            'cantidad' => \Cart::getTotalQuantity(),
            'total' => \Cart::getTotal(),
            'items' => \Cart::getContent()
        ];
        return $this->sendResponse($data, trans('admin.success'));
    }

    public function agregar($lang, Request $request)
    {
        try {
            $itemCarrito = null;
            if ($request->get('rowId',false)) {
                $itemCarrito = \Cart::get($request->rowId);
            }

            $item = Aniada::with('vino')->find($itemCarrito ? $itemCarrito->attributes->aniada_id : $request->id);
            //throw new \Exception("No hay suficiente stock para este producto", 1);
            if ($item && $item->enabled && $item->stock >= $request->cantidad) {
                $nombreItem = $item->vino->titulo . ' (' . $item->anio .')';
                $precio = $lang === 'es' ? $item->precio_pesos : $item->precio_usd;

                if ($itemCarrito) {
                    \Cart::update($request->rowId,['quantity' => [
                        'relative' => false,
                        'value' => $request->cantidad
                    ]]);
                } else {
                    \Cart::add([
                        'id' => md5($item->id), // inique row ID
                        'name' => $nombreItem,
                        'price' => $precio,
                        'quantity' => $request->cantidad,
                        'attributes' => [
                            'imagen' => $item->vino->imagen_url,
                            'aniada' => $item->anio,
                            'nombre_vino' => $item->vino->titulo,
                            'aniada_id' => $item->id,
                            'precio_usd' => $item->precio_usd,
                            'precio_pesos' => $item->precio_pesos
                        ]
                        ]);
                }

            } else {
                throw new \Exception("No hay suficiente stock para este producto", 1);

            }

            $data = [
                'cantidad' => \Cart::getTotalQuantity(),
                'total' => \Cart::getTotal(),
                'items' => \Cart::getContent()
            ];

            return $this->sendResponse($data, trans('admin.success'));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(),500);
        }

    }



    public function checkout()
    {
        /*
        $configuraciones = configuracionesPorPais();
        $precio = $configuraciones['precio'];

        $itemsCarrito = \Cart::content();
        if ($itemsCarrito) {
            $usuario = \Auth::user()->load('pais.provincias');
            $provincias = [];

            if ($usuario->pais->abreviatura === 'ar') {
                $provincias = Provincia::wherePaisId($usuario->pais->id)->get();
            }

            $ultPedido = $usuario->pedidos()->orderBy('id', 'desc')->first();
            $this->data['carritoCheckout'] = [
                'items' => $itemsCarrito,
                'form' => [
                    'nombre' => $usuario->nombre,
                    'apellido' => $usuario->apellido,
                    'email' => $usuario->email,
                    'telefono' => $ultPedido ? $ultPedido->telefono : null,
                    'direccion' => $ultPedido ? $ultPedido->direccion : null,
                    'ciudad' => $ultPedido ? $ultPedido->ciudad : null,
                    'cp' => $ultPedido ? $ultPedido->cp : null,
                    'provincia_id' => $ultPedido ? $ultPedido->provincia_id : $usuario->pais->provincias()->first()->id,
                    'pais_id' => $usuario->pais->id,
                    'pais' => $usuario->pais,
                    'acepto_tc' => false,
                    'precompletado' => $ultPedido ? true : false
                ],
                'envio' => [
                    'total' => null,
                    'url_calcular' => route('envio.calcular'),
                    'calculando' => false
                ],
                'total' => \Cart::count() * $precio,
                'cantidad' => \Cart::count(),
                'info' => [
                    'provincias' => $provincias
                ],
                'url_post' => route('carrito.checkoutPagar'),
                'precioLibro' => $precio,
                'enviando' => false,
                'enviado' => false
            ];
            return view('front.carrito.checkout', ['data' => $this->data]);
        } else {
            return redirect()->route('home');
        }
        */
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
            foreach (\Cart::content() as $cartItem) {
                $options = $cartItem->options->toArray();
                //\Log::info($options);
                $item = [
                    'identificador' => $options['identificador'],
                    'nombre' => $options['form']['nombre'],
                    'genero' => $options['form']['genero'],
                    'personaje' => $options['form']['personaje'],
                    'idioma' => $options['form']['idioma'],
                    'dedicatoria' => $options['form']['dedicatoria'],
                    'foto' => $options['form']['foto'],
                    'precio' => $precio,
                    'cuento' => $options['cuento']
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
                    'qty' => 1
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
