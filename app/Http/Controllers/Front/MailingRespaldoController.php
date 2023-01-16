<?php

namespace App\Http\Controllers\Front;

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

    public function registro($guid)
    {
        $registrado = Registrado::where(\DB::raw('md5(id)'),$guid)->first();
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.registro', ['registrado' => $registrado, 'respaldo' => true]);

    }

    public function registroConfirmado($guid)
    {
        $registrado = Registrado::where(\DB::raw('md5(id)'),$guid)->first();
        $markdown = new Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.registro-confirmado', ['registrado' => $registrado, 'respaldo' => true]);

    }    
}
