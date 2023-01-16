<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

use App\Services\UPSService;

Route::prefix('id')->group(function () {
    Route::get('/clear/{type}', 'IDController@clear');
    Route::get('/send-email', 'IDController@sendEmail');
});

Route::prefix('test')->group(function () {
    Route::get('/leer-s3', 'TestController@leerS3');
});

Route::prefix('combos')->group(function () {
    //Route::get('/vendedores', 'CombosController@vendedores')->name('combo.vendedores');
    //Route::get('/proveedores', 'CombosController@proveedores')->name('combo.proveedores');
});

Route::prefix('exportar')->group(function () {
    Route::get('/', 'Admin\ExportController@export')->name('export.general');
});

/*Uploads*/
Route::prefix('uploads')->group(function () {
    //Route::post('/file', 'UploadsController@storeFile')->name('uploads.store-file');
    Route::post('/file', 'UploadsController@subirArchivo')->name('uploads.store-file');
    Route::post('/image', 'UploadsController@storeImage')->name('uploads.store-image');
});

/*Admin*/
Route::prefix('/admin')->group(function () {
    Route::get('/login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Admin\Auth\LoginController@login')->name('admin.login.submit');

    Route::get('/password/reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset', 'Admin\Auth\ResetPasswordController@reset')->name('admin.reset.post');
    Route::get('/password/email', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.email');
    Route::post('/password/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.email.post');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');

        Route::post('usuarios/change-enabled', 'Admin\UserController@changeEnabled')->name('usuarios.change-enabled');
        Route::post('usuarios/filter', 'Admin\UserController@filter')->name('usuarios.filter');
        Route::get('usuarios/exportar/{type}', 'Admin\UserController@export')->name('usuarios.export');
        Route::put('usuarios/{id}/guardar-permisos', 'Admin\UserController@guardarPermisos')->name('usuarios.guardar-permisos');
        Route::get('usuarios/{id}/editar-permisos', 'Admin\UserController@editarPermisos')->name('usuarios.editar-permisos');

        Route::resource('usuarios', 'Admin\UserController');

        Route::post('roles/filter', 'Admin\RoleController@filter')->name('roles.filter');
        Route::resource('roles', 'Admin\RoleController');

        Route::get('legados/edit/{id}/{lang}', 'Admin\LegadosController@editLang')->name('legados.edit-lang');
        Route::post('legados/change-enabled', 'Admin\LegadosController@changeEnabled')->name('legados.change-enabled');
        Route::post('legados/filter', 'Admin\LegadosController@filter')->name('legados.filter');
        Route::resource('legados', 'Admin\LegadosController');

        Route::get('notas/edit/{id}/{lang}', 'Admin\NotaController@editLang')->name('notas.edit-lang');
        Route::post('notas/change-enabled', 'Admin\NotaController@changeEnabled')->name('notas.change-enabled');
        Route::post('notas/filter', 'Admin\NotaController@filter')->name('notas.filter');
        Route::resource('notas', 'Admin\NotaController');

        Route::get('home-sliders/edit/{id}/{lang}', 'Admin\HomeSliderController@editLang')->name('home-sliders.edit-lang');
        Route::post('home-sliders/change-enabled', 'Admin\HomeSliderController@changeEnabled')->name('home-sliders.change-enabled');
        Route::post('home-sliders/filter', 'Admin\HomeSliderController@filter')->name('home-sliders.filter');
        Route::resource('home-sliders', 'Admin\HomeSliderController');

        Route::get('newsletters/edit/{id}/{lang}', 'Admin\NewslettersController@editLang')->name('newsletters.edit-lang');
        Route::post('newsletters/change-enabled', 'Admin\NewslettersController@changeEnabled')->name('newsletters.change-enabled');
        Route::post('newsletters/filter', 'Admin\NewslettersController@filter')->name('newsletters.filter');
        Route::resource('newsletters', 'Admin\NewslettersController');

        Route::get('contactos/edit/{id}/{lang}', 'Admin\ContactosController@editLang')->name('contactos.edit-lang');
        Route::post('contactos/change-enabled', 'Admin\ContactosController@changeEnabled')->name('contactos.change-enabled');
        Route::post('contactos/filter', 'Admin\ContactosController@filter')->name('contactos.filter');
        Route::resource('contactos', 'Admin\ContactosController');

        Route::get('vinos/edit/{id}/{lang}', 'Admin\VinosController@editLang')->name('vinos.edit-lang');
        Route::post('vinos/change-enabled', 'Admin\VinosController@changeEnabled')->name('vinos.change-enabled');
        Route::post('vinos/filter', 'Admin\VinosController@filter')->name('vinos.filter');
        Route::resource('vinos', 'Admin\VinosController');

        Route::get('aniadas/edit/{id}/{lang}', 'Admin\AniadaController@editLang')->name('aniadas.edit-lang');
        Route::get('aniadas/{parentId}', 'Admin\AniadaController@index')->name('aniadas.index');
        Route::get('aniadas/{parentId}/create', 'Admin\AniadaController@create')->name('aniadas.create');
        Route::post('aniadas/{parentId}/store', 'Admin\AniadaController@store')->name('aniadas.store');
        Route::get('aniadas/{parentId}/{id}/edit', 'Admin\AniadaController@edit')->name('aniadas.edit');
        Route::put('aniadas/{id}/update', 'Admin\AniadaController@update')->name('aniadas.update');
        Route::get('aniadas/{parentId}/{id}/show', 'Admin\AniadaController@show')->name('aniadas.show');
        Route::delete('aniadas/{id}/destroy', 'Admin\AniadaController@destroy')->name('aniadas.destroy');
        Route::post('aniadas/change-enabled', 'Admin\AniadaController@changeEnabled')->name('aniadas.change-enabled');
        Route::post('aniadas/{parentId}/filter', 'Admin\AniadaController@filter')->name('aniadas.filter');

        Route::get('pedidos/edit/{id}/{lang}', 'Admin\PedidoController@editLang')->name('pedidos.edit-lang');
        Route::post('pedidos/change-enabled', 'Admin\PedidoController@changeEnabled')->name('pedidos.change-enabled');
        Route::post('pedidos/filter', 'Admin\PedidoController@filter')->name('pedidos.filter');
        Route::resource('pedidos', 'Admin\PedidoController');

        Route::get('packagings/edit/{id}/{lang}', 'Admin\PackagingController@editLang')->name('packagings.edit-lang');
        Route::post('packagings/change-enabled', 'Admin\PackagingController@changeEnabled')->name('packagings.change-enabled');
        Route::post('packagings/filter', 'Admin\PackagingController@filter')->name('packagings.filter');
        Route::resource('packagings', 'Admin\PackagingController');

        Route::get('pais/edit/{id}/{lang}', 'Admin\PaisController@editLang')->name('pais.edit-lang');
        Route::post('pais/change-enabled', 'Admin\PaisController@changeEnabled')->name('pais.change-enabled');
        Route::post('pais/filter', 'Admin\PaisController@filter')->name('pais.filter');
        Route::resource('pais', 'Admin\PaisController');

        Route::get('clear-cache', function () {
            $exitCode = Artisan::call('cache:clear');
            echo 'done';// return what you want
        })->name('clear-cache');

        Route::get('/error/{code}', 'Admin\ErrorController@index')->name('admin.error');



        Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin.home');

    });
});

Route::get('/log/obtener', function () {
    if (env('APP_ENV', 'local') === 'local') {
        $pathToFile = storage_path() . '\logs\laravel.log';
    } else {
        $pathToFile = storage_path() . '/logs/laravel.log';
    }

    return response()->download($pathToFile, 'laravel.log');
});
Route::get('/log/borrar', function () {
    if (env('APP_ENV', 'local') === 'local') {
        $pathToFile = storage_path() . '\logs\laravel.log';
    } else {
        $pathToFile = storage_path() . '/logs/laravel.log';
    }

    unlink($pathToFile);
    return 'Listo.';
});

Route::prefix('mailing/respaldo')->group(function () {
    Route::get('/registro/{guid}', 'Front\MailingRespaldoController@registro')->name('mailingRespaldo.registro');
});



//Route::middleware(['check.country'])->group(function () {
Route::get('/cambiar-idioma/{lang}', 'Front\HomeController@cambiarIdioma')->name('cambiarIdioma');
Route::group(['prefix' => 'servicios'],function () {
    Route::post('/newsletter/guardar', '\App\Services\NewsletterService@guardar')->name('service.newsletter.guardar');
    Route::post('/contacto/guardar', '\App\Services\ContactoService@guardar')->name('service.contacto.guardar');
});
//});
$locale = config('app.locale');

if (request()->segment(1) !== 'admin') {
    config(['translatable.use_fallback' => true]);
} else {
    config(['translatable.use_fallback' => false]);
    app()->setLocale($locale);
    Date::setLocale($locale);
}


Route::get('/', 'Front\HomeController@index')->name('home');
Route::get('/ups/direccion/{codigoPais}/{codigoPostal}', '\App\Services\UPSService@cotizarEnvio');
Route::get('/ups/envio', function () {
    $producto = \App\Aniada::whereId(2)->first();
    $productos = [];
    for($x=0;$x<1;$x++) {
        array_push($productos, $producto);
    }

    $srv = new UPSService();
    dd($srv->cotizarEnvio('AR', '1429', $productos));
});
Route::get('/dolar/oficial', function (){
    echo(obtenerDolarOficial());
});

Route::group(['prefix' => '{lang}', 'where' => ['lang' => 'en|es|pt']],function (){
    if (request()->segment(1) !== 'admin') {
        if (\Cookie::get('uco_idioma',null) === null) {
            //me fijo por el primer segmento de la URL que idioma quiere ver
            $locale = request()->segment(1);
        } else {
            $currentPath = '/'.request()->path();
            $homePath = '/'.app()->getLocale();

            if ($currentPath === $homePath) {
                //Si es en el root, entonces se le da bola al idioma que esta en la cookie
                $locale = \Cookie::get('uco_idioma');
            } else {
                // SIno se le da bola al que esta en el request
                $locale = request()->segment(1);
            }
        }

        if (in_array($locale, config('translatable.locales'))) {
            app()->setLocale($locale);
            Date::setLocale($locale);
        }
    }
    Route::middleware(['guest'])->group(function () {
        //Route::get('/confirmar-cuenta/{guid}', 'Front\MiCuentaController@confirmarCuenta')->name('confirmarCuenta');
        Route::get('/login', 'Front\MiCuentaController@login')->name('login');
        Route::post('/login', 'Auth\LoginController@login')->name('login-post');
        Route::get('/registro', 'Front\MiCuentaController@registro')->name('registro');
        Route::post('/registro', 'Auth\RegisterController@register')->name('registro-post');
    });

    Route::get('/', 'Front\HomeController@index')->name('home');
    Route::get(trans('front.rutas.legado'), 'Front\LegadoController@index')->name('legado');
    Route::get(trans('front.rutas.nuestroCompromiso').'/{slide?}', 'Front\NuestroCompromisoController@index')->name('nuestroCompromiso');

    Route::get(trans('front.rutas.colecciones.tucci').'/{id?}/{slug?}', 'Front\ColeccionesController@tucci')->name('colecciones.tucci');
    Route::get(trans('front.rutas.colecciones.interwine').'/{id?}/{slug?}', 'Front\ColeccionesController@interwine')->name('colecciones.interwine');
    Route::get(trans('front.rutas.colecciones.root'), 'Front\ColeccionesController@index')->name('colecciones');

    Route::get(trans('front.rutas.francescaTucci'), 'Front\FrancescaTucciController@index')->name('francescaTucci');
    Route::get(trans('front.rutas.novedades').'/{id?}', 'Front\NovedadesController@index')->name('novedades');
    Route::get(trans('front.rutas.contacto'), 'Front\ContactoController@index')->name('contacto');

    Route::get(trans('front.rutas.pp'), 'Front\FrontController@politicasPrivacidad')->name('politicasPrivacidad');
    Route::get(trans('front.rutas.tyc'), 'Front\FrontController@terminosCondiciones')->name('terminosCondiciones');

    Route::prefix(trans('front.rutas.carrito.root'))->group(function () {
        Route::get(trans('front.rutas.carrito.detalle'), 'Front\CarritoController@index')->name('carrito');
        Route::post(trans('front.rutas.carrito.agregar'), 'Front\CarritoController@agregar')->name('carrito.agregar');
        Route::post(trans('front.rutas.carrito.quitar').'/{id}', 'Front\CarritoController@quitar')->name('carrito.quitar');
        /*Route::prefix('/checkout')->middleware(['checkout'])->group(function () {
            Route::get('/', 'Front\CarritoController@checkout')->name('carrito.checkout');
            Route::post('/pagar', 'Front\CarritoController@checkoutPagar')->name('carrito.checkoutPagar');
        });*/
    });
    Route::middleware(['checkout'])->group(function () {
        Route::prefix(trans('front.rutas.checkout.root'))->group(function () {
            Route::get(trans('front.rutas.checkout.gracias').'/{guid}', 'Front\CheckoutController@gracias')->name('checkout.gracias');
            Route::post(trans('front.rutas.checkout.confirmar'), 'Front\CheckoutController@confirmar')->name('checkout.confirmar');
            Route::post(trans('front.rutas.checkout.cotizarEnvio'),'Front\CheckoutController@cotizarEnvio')->name('checkout.cotizarEnvio');
            Route::get('/', 'Front\CheckoutController@index')->name('checkout');
        });
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });

    Route::middleware(['auth'])->group(function () {
        Route::prefix(trans('front.rutas.miCuenta.root'))->group(function () {
            Route::get('/', 'Front\MiCuentaController@index')->name('miCuenta');
            Route::get(trans('front.rutas.miCuenta.misDatos'), 'Front\MiCuentaController@misDatos')->name('miCuenta.misDatos');
            Route::post(trans('front.rutas.miCuenta.misDatos'), 'Front\MiCuentaController@guardarMisDatos')->name('miCuenta.misDatos.guardar');
            Route::get(trans('front.rutas.miCuenta.cambiarPassword'), 'Front\MiCuentaController@cambiarPassword')->name('miCuenta.cambiarPassword');
            Route::post(trans('front.rutas.miCuenta.cambiarPassword'), 'Front\MiCuentaController@guardarCambiarPassword')->name('miCuenta.cambiarPassword.guardar');

            Route::get(trans('front.rutas.miCuenta.pedidos'), 'Front\MiCuentaController@pedidos')->name('miCuenta.pedidos');
            Route::get(trans('front.rutas.miCuenta.pedidos').'/{id}', 'Front\MiCuentaController@detallePedido')->name('miCuenta.pedidos.detalle');
            Route::post(trans('front.rutas.miCuenta.direcciones'), 'Front\MiCuentaController@guardarDireccion')->name('miCuenta.direcciones.guardar');
        });
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    });
});
Route::post('/olvide-contrasena', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('olvide-password');





