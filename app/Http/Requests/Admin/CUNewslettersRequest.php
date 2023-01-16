<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Newsletters;

class CUNewslettersRequest extends FormRequest
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
        $rules = Newsletters::$rules;
        $rules['email'] = str_replace('{:id}', $this->get('id') , $rules['email']); 
        return $rules;
    }

    public function messages()
    {
        return trans('front.modulos.suscripcion.validacion');
    }
}
