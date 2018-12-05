<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class FornecedorRequest extends FormRequest
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

        $cpf_cnpj = '';

        if($this->tipo_fornecedor == 'FISICA' or $this->tipo_fornecedor == 'JURIDICA'){
            $cpf_cnpj = 'cpf_cnpj|';
        }

        return [
            'tipo_fornecedor' => "required",
            'cpf_cnpj_idgener' => "required|{$cpf_cnpj}unique:fornecedores,cpf_cnpj_idgener,{$id}",
            'nome' => 'required|max:255',
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
            'tipo_fornecedor.required' => 'O campo "Tipo Fornecedor" é obrigatório!',
            'cpf_cnpj_idgener.required' => 'O campo "CPF/CNPJ/UG/ID Genérico" é obrigatório!',
            'cpf_cnpj_idgener.cpf_cnpj' => 'CPF/CNPJ inválido!',
            'cpf_cnpj_idgener.unique' => 'Este CPF/CNPJ/UG/ID Genérico já está cadastrado!',
            'nome.required' => 'O campo "Nome" é obrigatório!',
            'nome.max' => 'O campo "Nome" deve ser no máximo 255 caracteres!',
        ];
    }
}
