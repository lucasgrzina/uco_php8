<?php

namespace App\Http\Controllers\Auth;

use App\Registrado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        if ($user = Registrado::where('email', $request->input('email'))->first()) {
            $clave = randomPassword();
            $user->password = $clave;
            $user->save();
            /*DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $token
            ]);*/

            $user->sendPasswordResetNotification($clave);

            return  $this->sendResetLinkResponse(null);
        }

        return $this->sendResetLinkFailedResponse($request, null);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(ResponseUtil::makeError('Revisá el email ingresado ya que es inválido o no se encuentra registrado'), 404);
    }

    protected function sendResetLinkResponse($response)
    {
        return response()->json(ResponseUtil::makeResponse(trans('front.paginas.login.recuperar.teHemos'), ['message' => trans('front.paginas.login.recuperar.teHemos')]));
    }
}
