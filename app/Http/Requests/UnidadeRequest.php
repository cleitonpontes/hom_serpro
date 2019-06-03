<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class UnidadeRequest extends FormRequest
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

        return [
            'orgao_id' => "required",
            'codigo' => "required|max:6|min:6|unique:unidades,codigo,{$id}",
            'gestao' => "required|max:5|min:5",
            'codigosiasg' => "required|max:6|min:6|unique:unidades,codigosiasg,{$id}",
            'nome' => "required|max:255",
            'nomeresumido' => "required|max:10",
            'tipo' => 'required',
            'situacao' => 'required',
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
            'codigo.required' => 'O campo "Código SIAFI" é obrigatório!',
            'gestao.required' => 'O campo "Código SIAFI" é obrigatório!',
            'codigo.unique' => 'Este Código SIAFI já está cadastrado!',
            'codigosiasg.required' => 'O campo "Código SIASG" é obrigatório!',
            'codigosiasg.unique' => 'Este Código SIASG já está cadastrado!',
            'nome.required' => 'O campo "Nome" é obrigatório!',
            'nomeresumido.required' => 'O campo "Nome Resumido" é obrigatório!',
            'nome.max' => 'O campo "Nome" deve ser no máximo 255 caracteres!',
            'nomeresumido.max' => 'O campo "Nome Resumido" deve ser no máximo 10 caracteres!',
            'tipo.required' => 'O campo "Situação" é obrigatório!',
            'situacao.required' => 'O campo "Situação" é obrigatório!',
        ];
    }
}
