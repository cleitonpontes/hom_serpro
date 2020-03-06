<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ContratofaturaRequest extends FormRequest
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
            'contrato_id' => 'required',
            'tipolistafatura_id' => 'required',
            'numero' => 'required|max:17',
            'emissao' => 'required|date',
            'vencimento' => 'required|date',
            'valor' => 'required',
            'processo' => 'required|max:20',
            'protocolo' => 'required|date',
            'ateste' => 'required|date',
            'repactuacao' => 'required',
            'mesref' => 'required',
            'anoref' => 'required',
            'infcomplementar' => 'max:255',
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
            //
        ];
    }
}
