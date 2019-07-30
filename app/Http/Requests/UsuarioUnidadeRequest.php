<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioUnidadeRequest extends FormRequest
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
            'cpf' => "required|cpf|unique:users,cpf,{$id}",
            'name' => 'required|max:255',
            'email' => "required|email|max:255|unique:users,email,{$id}",
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
            'cpf.required' => 'O campo "CPF" é obrigatório!',
            'cpf.cpf' => 'CPF inválido!',
            'cpf.unique' => 'Este CPF já está cadastrado!',
            'name.required' => 'O campo "Nome" é obrigatório!',
            'name.max' => 'O campo "Nome" deve ser no máximo 255 caracteres!',
            'email.unique' => 'Este E-mail já está cadastrado!',
            'email.email' => 'E-mail inválido!',
            'email.required' => 'O campo "E-mail" é obrigatório!',
            'email.max' => 'O campo "E-mail" deve ser no máximo 255 caracteres!',
        ];
    }
}
