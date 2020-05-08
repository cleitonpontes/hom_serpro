<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ImportacaoRequest extends FormRequest
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
            'nome_arquivo' => 'required|max:255',
            'tipo_id' => 'required',
            'unidade_id' => 'required',
            'contrato_id' => 'required',
            'situacao_id' => 'required',
            'delimitador' => 'required|max:1' ,
            'arquivos.*' => 'required',
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
            'nome_arquivo' => 'Nome do Arquivo',
            'tipo_id' => 'Tipo',
            'unidade_id' => 'Unidade Gestora',
            'contrato_id' => 'Contrato',
            'delimitador' => 'Delimitador' ,
            'arquivos.*' => 'Arquivos',
            'situacao_id' => 'Situação',
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
            'delimitador.max' => "O campo :attribute não pode ser superior a 1 caractere.",

        ];
    }
}
