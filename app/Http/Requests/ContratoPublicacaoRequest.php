<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\NaoAceitarFeriado;
use App\Rules\NaoAceitarFimDeSemana;
use Illuminate\Foundation\Http\FormRequest;

class ContratoPublicacaoRequest extends FormRequest
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
            'data_publicacao' => [
                'required',
                new NaoAceitarFeriado(), new NaoAceitarFimDeSemana()
            ],
            'texto_dou' => 'required',
            'tipo_pagamento_id' => 'required',
            'motivo_isencao_id' => 'required',
            'numero' => 'required_if:tipo_pagamento_id,376',
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
            'texto_dou' => 'Texto DOU',
            'tipo_pagamento_id' => 'Tipo Pagamento',
            'motivo_isencao_id' => 'Motivo Isenção',
            'numero' => 'Número Empenho',
            'data_publicacao' => 'Data Publicacao',
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
            'numero.required_if' => 'O campo :attribute é obrigatório quando Tipo Pagamento é Empenho.'
        ];
    }
}
