<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\NaoAceitarEstrangeiro;
use Illuminate\Foundation\Http\FormRequest;

class MinutaEmpenhoRequest extends FormRequest
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
        $this->data_hoje = date('Y-m-d');
        $this->data_ano = date('Y');

        return [
            'numero_empenho_sequencial' => 'nullable|numeric|between:400001,800000',
            'descricao' => 'required|max:468',
//            'local_entrega'=> 'required',
            'taxa_cambio' => 'required',
            'amparo_legal_id' => 'required',
            'processo' => 'max:20',
//            'fornecedor_empenho_id' => 'not_regex:/ESTRANGEIRO/',
            'fornecedor_empenho_id' => [
                new NaoAceitarEstrangeiro()
            ],
            'data_emissao' => "date|before_or_equal:{$this->data_hoje}",

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
            'numero_empenho_sequencial' => 'Número Empenho',
            'descricao' => 'Descrição / Observação',
            'amparo_legal_id' => 'Amparo Legal',
            'processo' => 'Número Processo',
            'data_emissao' => 'Data Emissão',
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
            'numero_empenho_sequencial.min' => 'O :attribute deve maior que 400001',
            'numero_empenho_sequencial.max' => 'O campo :attribute não pode ser maior que 800000',
            'data_emissao.before_or_equal' => "O campo :attribute deve ser uma data anterior ou igual a "
                . date('d/m/Y'),
        ];
    }
}
