<?php

namespace App\Http\Controllers\Auth;

use App\Registrado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        try {
            $user = $this->create($request->all());
            event(new Registered($user));
            $this->guard()->login($user);

            $data = [
                'user' => $this->guard()->user(),
                'url_redirect' => false
            ];

            return response()->json(ResponseUtil::makeResponse('La operación finalizó con éxito', $data));
    
        } catch (\Exception $e) {
            return response()->json(ResponseUtil::makeError($e->getMessage(), []));
        }
        

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = Registrado::$rules;
        $rules['email'] = str_replace('{:id}', 0, $rules['email']);
        $messages = [
            'usuario.unique' => ' El usuario ya está siendo utilizado por otro usuario.',
            'email.unique' => ' Esta dirección de email ya está siendo utilizada por otro usuario.',
            'password.regex' => 'La contraseña debe contener al menos 6 caracteres, una mayúscula, minúsculas y un número.',
            'password.confirmed' => 'Las contraseñas ingresadas no coinciden.',
        ];
        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //$data['usuario'] = $data['email'];
        //$data['confirmado'] = 2;
        //\Log::info($data);
        try {
            DB::beginTransaction();
            $model = Registrado::create($data);

            //$model->enviarNotificacionRegistro();

            //throw new \Exception("Error Processing Request", 1);
            DB::commit();
            return $model;
    
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e->getMessage());
            throw $e;
        }
        
    }
}
