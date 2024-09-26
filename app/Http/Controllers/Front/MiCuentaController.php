<?php

namespace App\Http\Controllers\Front;

use Auth;
use App\Registrado;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\PedidoRepository;
use App\Repositories\RegistradoRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Front\CambiarPasswordRequest;
use App\Http\Requests\Front\GuardarMisDatosRequest;
use App\Http\Requests\Front\GuardarDireccionRequest;
use App\Http\Requests\Front\MiCuentaMisDatosGuardarRequest;

class MiCuentaController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $repoRegistrados = null;

    public function __construct(RegistradoRepository $repoRegistrados)
    {
        //$this->middleware('auth:admin');
        $this->repoRegistrados = $repoRegistrados;
    }

    public function index($lang)
    {
        $this->data['miCuenta'] = [
        ];
        return view('front.mi-cuenta.index', ['data' => $this->data]);
    }

    public function misDatos($lang)
    {
        $this->data['registro'] = [
            'vista' => 'datos',
            'titulo' => trans('front.paginas.miCuenta.misDatos.titulo'),
            'form' => [
                'usuario' => auth()->user()->usuario,
                'email' => auth()->user()->email,
                //'password' => null,
                //'password_confirmation' => null,
            ],
            'enviando' => false,
            'enviado' => false,
            'url_post' => routeIdioma('miCuenta.misDatos.guardar')
        ];
        return view('front.mi-cuenta.mis-datos', [
            'tituloPagina' => trans('front.paginas.miCuenta.misDatos.titulo'),
            'dataSection' => 'registro',
            'data' => $this->data
        ]);
    }


    public function guardarMisDatos($lang, GuardarMisDatosRequest $request) {
        try {
            DB::beginTransaction();
            auth()->user()->update([
                'usuario' => $request->usuario,
                'email' => $request->email,
            ]);
            DB::commit();
            return $this->sendResponse([],'');
        } catch (\Exception $e) {
            DB::rollback();
            logger($e->getMessage());
            $this->sendError($e->getMessage(),$e->getCode());
        }
    }

    public function cambiarPassword($lang)
    {
        $this->data['registro'] = [
            'vista' => 'password',
            'titulo' => trans('front.paginas.miCuenta.cambiarPassword.titulo'),
            'form' => [
                'password' => null,
                'password_confirmation' => null,
            ],
            'enviando' => false,
            'enviado' => false,
            'url_post' => routeIdioma('miCuenta.cambiarPassword.guardar')
        ];
        return view('front.mi-cuenta.mis-datos', [
            'tituloPagina' => trans('front.paginas.miCuenta.cambiarPassword.titulo'),
            'dataSection' => 'registro',
            'data' => $this->data
        ]);
    }


    public function guardarCambiarPassword($lang, CambiarPasswordRequest $request) {
        try {
            DB::beginTransaction();
            $registrado = auth()->user();
            $registrado->password = $request->password;
            $registrado->save();
            DB::commit();

            $registrado->sendPasswordConfirmationNotification();
            return $this->sendResponse([],'');
        } catch (\Exception $e) {
            DB::rollback();
            $this->sendError($e->getMessage(),$e->getCode());
        }
    }

    public function pedidos($lang,PedidoRepository $pedidosRepo)
    {
        $this->data['pedidos'] = [
            'listado' => $pedidosRepo->whereRegistradoId(auth()->user()->id)->orderBy('created_at','desc')->get(),
            'url_detalle' => routeIdioma('miCuenta.pedidos.detalle',['_ID_'])
        ];
        return view('front.mi-cuenta.pedidos', [
            'data' => $this->data,
            'tituloPagina' => trans('front.paginas.miCuenta.pedidos.titulo'),
            'dataSection' => 'mis-pedidos',
        ]);
    }

    public function detallePedido($lang,$pedidoId,PedidoRepository $pedidosRepo)
    {

        $pedido = $pedidosRepo->with([
            'items.aniada.vino'
        ])->whereRegistradoId(auth()->user()->id)->whereId($pedidoId)->first();

        if (!$pedido) {
            return redirect()->to(routeIdioma('miCuenta.pedidos'));
        }

        $this->data['pedidos'] = [
            'pedido' => $pedido,
            'url_listado' => routeIdioma('miCuenta.pedidos')
        ];
        return view('front.mi-cuenta.detalle-pedido', [
            'data' => $this->data,
            'tituloPagina' => trans('front.paginas.miCuenta.detallePedido.titulo'),
            'dataSection' => 'checkout',
        ]);
    }

    public function login($lang)
    {
        $this->data['login'] = [
            'vista' => 'login',
            'form' => [

                'usuario' => null,
                'password' => null,
            ],
            'formRecuperar' => [
                'email' => null,
            ],
            'enviando' => false,
            'enviado' => false,
            'url_post' => routeIdioma('login-post'),
            'url_post_recuperar' => routeIdioma('olvide-password')
        ];
        return view('front.mi-cuenta.login', [
            'tituloPagina' => trans('front.paginas.login.titulo'),
            'dataSection' => 'registro',
            'data' => $this->data
        ]);
    }

    public function registro($lang)
    {
        $this->data['registro'] = [
            'subtituloSeccion' => 'Completa el siguiente formulario con tus datos.',
            'form' => [
                'usuario' => null,
                'email' => null,
                'password' => null,
                'password_confirmation' => null,
            ],
            'enviando' => false,
            'enviado' => false,
            'url_post' => routeIdioma('registro-post')
        ];
        return view('front.mi-cuenta.registro', [
            'tituloPagina' => trans('front.paginas.registro.titulo'),
            'dataSection' => 'registro',
            'data' => $this->data
        ]);
    }




    public function confirmarCuenta($guid) {

        $registrado = Registrado::where(\DB::raw('md5(id)'),$guid)->first();

        if (!$registrado) {
            //
        }

        if ($registrado && !$registrado->confirmado) {
            $registrado->confirmado = true;
            $registrado->save();
        }

        Auth::login($registrado);

        return redirect()->route('home');

    }

    public function registroGracias() {
        if (auth()->check()) {
            auth()->logout();
        }
        $this->data['gracias'] = [
            'titulo' => 'Muchas gracias',
            'subtitulo' => ('En los próximos minutos validaremos los datos de registro y te enviaremos un email a tu casilla de correo.<br>Si no encuentras el mail en la bandeja de entrada, por favor chequea en la bandeja de correo no deseado y Spam.<br><br>Si tienes alguna duda o consulta, nos puedes escribir a <a style="color:#fff;text-decoration:underline;" href="mailto:contacto@ruta365.live">contacto@ruta365.live</a>')
        ];
        return view('front.gracias', ['data' => $this->data]);
    }

    public function guardarDireccion(GuardarDireccionRequest $request) {
        try {
            DB::beginTransaction();
            $direccion = $this->repoRegistrados->guardarDireccion($request->except(['pais','_token']));
            DB::commit();
            return $this->sendResponse($direccion,'');
        } catch (\Exception $e) {
            DB::rollback();
            logger($e->getMessage());
            $this->sendError($e->getMessage(),500);
        }
    }

    public function eliminarDireccion(Request $request) {
        try {
            DB::beginTransaction();
            $direccion = $this->repoRegistrados->eliminarDireccion($request->id);
            DB::commit();
            return $this->sendResponse([],'');
        } catch (\Exception $e) {
            DB::rollback();
            logger($e->getMessage());
            $this->sendError($e->getMessage(),500);
        }
    }
}
