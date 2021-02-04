<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CompraSiasgRequest extends FormRequest
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
             'id' => 'required_if:tipoEmpenho,1',
             'unidade_origem_id' => 'required_if:tipoEmpenho,2',
             'modalidade_id' => 'required_if:tipoEmpenho,2',
             'numero_ano' => 'required_if:tipoEmpenho,2',
             'fornecedor_empenho_id' => 'required_if:tipoEmpenho,3',
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
            'id' => "Contrato",
            'unidade_origem_id' => "Unidade Compra",
            'modalidade_id' => "Modalidade Licitação",
            'numero_ano' => "Numero / Ano",
            'fornecedor_empenho_id' => "Suprido",
            'tipoEmpenho' => "Tipo",
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
            'id.required_if' => 'O campo :attribute é obrigatório quando Tipo é Contrato.',
            'unidade_origem_id.required_if' => 'O campo :attribute é obrigatório quando Tipo é Compra.',
            'modalidade_id.required_if' => 'O campo :attribute é obrigatório quando Tipo é Compra.',
            'numero_ano.required_if' => 'O campo :attribute é obrigatório quando Tipo é Compra.',
            'fornecedor_empenho_id.required_if' => 'O campo :attribute é obrigatório quando Tipo é Suprimento.',
        ];
    }
}
