<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class EmpenhoRequest extends FormRequest
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
        $id = $this->id ?? "NULL";
        $unidade_id = $this->unidade_id;

        return [
            'numero' => [
                'required',
                (new Unique('empenhos','numero'))
                    ->ignore($id)
                    ->where('unidade_id',$unidade_id)
            ],
            'unidade_id' => 'required',
            'fornecedor_id' => 'required',
            'naturezadespesa_id' => 'required',
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
            'numero.required' => 'O campo "Número Empenho" é obrigatório!',
            'numero.unique' => 'Este Número de Empenho já está cadastrado!',
            'unidade_id.required' => 'O campo "Unidade Gestora" é obrigatório!',
            'fornecedor_id.required' => 'O campo "Credor / Fornecedor" é obrigatório!',
            'naturezadespesa_id.required' => 'O campo "Natureza Despesa (ND)" é obrigatório!',
        ];
    }
}
