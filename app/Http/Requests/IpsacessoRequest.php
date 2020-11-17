<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class IpsacessoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orgao_id' => "required",
//            'unidade_id' => 'required',
            'ips' => 'required|min:14',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'orgao_id.required' => 'O campo "Órgão" é obrigatório!',
//            'unidade_id.required' => 'O campo "Unidade" é obrigatório!',
            'ips.min' => 'Deve ser informado pelo menos 1 Número de IP',
        ];
    }
}
