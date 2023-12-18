<?php

namespace App\Http\Controllers\Front;

use App\Aniada;
//use App\Http\Requests\Front\CheckoutPagarRequest;
//use App\Repositories\PedidosRepository;
//use App\Services\ConfiguracionesService;
//use App\Services\EnvioService;
use App\Configuraciones;
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
        $configuraciones = Configuraciones::whereIn('clave',['COMPRAS_SUPERIORES'])->pluck('valor','clave')->toArray();
        $itemsCarrito = \Cart::getContent();

        $this->data['detalleCarrito'] = [
            'info' => [
            ],
        ];
        $this->data['configuraciones'] = $configuraciones;
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
                //$precio = $lang === 'es' ? $item->precio_pesos : $item->precio_usd;
                $precio = $lang === 'es' ? $item->precio_pesos : $item->precio_pesos;

                if ($itemCarrito) {

                    \Cart::update($request->rowId,['quantity' => [
                        'relative' => false,
                        'value' => $request->cantidad
                    ]]);
                } else {
                    $existe = \Cart::get(md5($item->id));
                    if ($existe) {
                        \Cart::remove($existe->id);
                    }


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
    }

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
