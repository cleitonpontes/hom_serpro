<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class JustificativafaturaRequest extends FormRequest
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
            'nome' => "required|min:5|unique:justificativafatura,nome,{$id}",
            'descricao' => "required",
            'situacao' => 'required'
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
            'nome.required' => 'O campo "Nome" é obrigatório!',
            'nome.min' => 'Nome deve ter no mínimo 05 caracteres!',
            'nome.unique' => 'Este Nome de Justificativa já está sendo utilizada!',
            'descricao.required' => 'O campo "Descrição" é obrigatório!',
            'situacao.required' => 'O campo "Situação" é obrigatório!',
        ];
    }
}
