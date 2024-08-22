<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Pedido;

class CUPedidoRequest extends FormRequest
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
        if (isset($this->accion) && $this->accion == "cambiarValor") {
            $rules =  [];
        } else {
            $rules = Pedido::$rules;
        }

        return $rules;
    }
}
