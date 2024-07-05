<?php

namespace App\Http\Controllers\Front;

use App\Pedido;
use App\Registrado;
use Illuminate\Mail\Markdown;
use App\Http\Controllers\AppBaseController;

class MailingRespaldoController extends AppBaseController
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

    public function registro($id,$locale='es')
    {
        app()->setLocale($locale);
        $registrado = Registrado::find($id);
        $markdown = new Markdown(view(), config('mail.markdown'));

        try
        {
            //$registrado->enviarNotificacionRegistro($locale);
        }
        catch(\Exception $ex)
        {
            \Log::error($ex->getMessage());
        }
        return $markdown->render('emails.registro', ['registrado' => $registrado, 'respaldo' => true]);

    }

    public function recuperar($id,$locale='es')
    {
        app()->setLocale($locale);
        $registrado = Registrado::find($id);
        $markdown = new Markdown(view(), config('mail.markdown'));

        try
        {
            //$registrado->sendPasswordResetNotification('holis');
        }
        catch(\Exception $ex)
        {
            \Log::error($ex->getMessage());
        }
        return $markdown->render('emails.reset-password', ['clave' => 'aaa','user' => $registrado, 'respaldo' => true]);

    }

    public function pedido($id,$locale='es')
    {
        app()->setLocale($locale);
        $pedido = Pedido::find($id);
        $markdown = new Markdown(view(), config('mail.markdown'));

        try
        {
            //$pedido->registrado->enviarNotificacionPedido($pedido);
        }
        catch(\Exception $ex)
        {
            \Log::error($ex->getMessage());
        }
        return $markdown->render('emails.pedido', ['pedido' => $pedido,'respaldo' => true]);

    }

    public function registroConfirmado($guid)
    {
        $registrado = Registrado::where(\DB::raw('md5(id)'),$guid)->first();
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.registro-confirmado', ['registrado' => $registrado, 'respaldo' => true]);

    }

    public function confirmarPass($id,$locale='es')
    {
        app()->setLocale($locale);
        $registrado = Registrado::find($id);
        $markdown = new Markdown(view(), config('mail.markdown'));

        try
        {
            //$registrado->sendPasswordResetNotification('holis');
        }
        catch(\Exception $ex)
        {
            \Log::error($ex->getMessage());
        }
        return $markdown->render('emails.confirm-password', ['user' => $registrado, 'respaldo' => true]);

    }
}
