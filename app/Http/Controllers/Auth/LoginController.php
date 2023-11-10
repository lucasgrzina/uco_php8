<?php

namespace App\Http\Controllers\Auth;

use App\Registrado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $username;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    public function username()
    {
        return $this->username;
    }

    public function findUsername()
    {
        $login = request()->input('usuario');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'usuario';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    protected function credentials(Request $request)
    {

        $credentials = $request->only($this->username(), 'password');
        // Customization: validate if client status is active (1)
        return $credentials;
    }

    protected function validateLogin(Request $request)
    {
        $rules = \Arr::only(Registrado::$rules,['usuario','password']);
        $rules['usuario'] = str_replace('|unique:registrados,usuario,{:id},id','',$rules['usuario']);
        $rules['password'] = str_replace('required_if:id,0|','required|',$rules['password']);
        $rules['password'] = str_replace('confirmed|','',$rules['password']);

        $messages = [
            'password.regex' => trans('front.paginas.login.formatoPassword')
        ];
        $this->validate($request, $rules,$messages);
    }

    public function login($lang,Request $request)
    {
        //$request->merge(['confirmado' => true]);

        $this->validateLogin($request);



        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            // Customization: Validate if client status is active (1)
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $user = $this->guard()->user();
        try {
            $user->last_login_at = date('Y-m-d H:i:s');
            $user->last_login_ip = $request->ip();
            $user->save();

        }catch(\Exception $e) {

        }

        $data = [
            'user' => $user,
            'url_redirect' => routeIdioma('home')
        ];
        return response()->json(ResponseUtil::makeResponse('La operación finalizó con éxito', $data));
    }

    public function logout($lang,Request $request)
    {
        $this->guard()->logout();

        return redirect()->to(routeIdioma('home'));
    }
}
