<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class OrgaoSuperiorRequest extends FormRequest
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
            'codigo' => "required|max:5|min:5|unique:orgaossuperiores,codigo,{$id}",
            'nome' => "required|max:255",
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
            'codigo.unique' => 'Este Código já está cadastrado!',
            'nome.required' => 'O campo "Nome" é obrigatório!',
            'nome.max' => 'O campo "Nome" deve ser no máximo 255 caracteres!',
            'situacao.required' => 'O campo "Situação" é obrigatório!',
        ];
    }
}
