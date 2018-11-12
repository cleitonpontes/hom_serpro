<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
            'cpf' => 'required|cpf|unique:users,cpf',
            'name' => 'required|max:255',
            'ugprimaria' => 'max:6',
            'email' => 'required|email|max:255|unique:users,email'
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
            'name.required' => 'O Nome é um campo obrigatório!',
            'username.required' => 'O CPF é um campo obrigatório!',
            'username.unique' => 'Este CPF já está cadastrado!',
            'email.required' => 'O E-mail é um campo obrigatório!',
            'email.unique' => 'Este E-mail já está cadastrado!',
            'username.cpf' => 'O CPF informado não é válido!'
        ];
    }
}
