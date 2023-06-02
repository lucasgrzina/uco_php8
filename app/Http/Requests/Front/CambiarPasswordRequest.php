<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;
use App\Registrado;

class CambiarPasswordRequest extends FormRequest
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
        $rules = \Arr::only(Registrado::$rules,['password']);
        $rules['password'] = explode('|',$rules['password']);
        array_shift($rules['password']);
        //logger($rules);
        return $rules;
    }
}
