<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class RhrubricaRequest extends FormRequest
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
             'codigo' => "required|unique:rhrubrica,codigo,{$id}",
             'descricao' => 'required',
             'criacao' => 'required',
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
            'codigo.required' => 'O campo "Código" é obrigatório!',
            'codigo.unique' => 'Este "Código" já está cadastrado!',
            'descricao.required' => 'O campo "Descriçao" é obrigatório!',
            'tipo.required' => 'O campo "Tipo" é obrigatório!',
            'criacao.required' => 'O campo "Criação" é obrigatório!',
            'situacao.required' => 'O campo "Situação" é obrigatório!',
        ];
    }
}
