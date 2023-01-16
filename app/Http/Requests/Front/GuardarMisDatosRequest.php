<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use App\Registrado;

class GuardarMisDatosRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = auth()->check() ? auth()->user()->id : null;
        $rules = \Arr::only(Registrado::$rules,['usuario','email']);
        $rules['email'] = str_replace('{:id}', $id , $rules['email']);
        $rules['usuario'] = str_replace('{:id}', $id , $rules['usuario']);
        return $rules;
    }
}
