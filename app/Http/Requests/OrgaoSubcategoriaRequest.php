<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class OrgaoSubcategoriaRequest extends FormRequest
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
             'orgao_id' => 'required',
             'categoria_id' => 'required',
             'descricao' => 'required|min:3|max:255',
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
            'categoria_id.required' => 'O campo "Categoria" é obrigatório!',
            'descricao.required' => 'O campo "Subcategoria" é obrigatório!',
            'descricao.max' => 'O campo "Subcategoria" deve ser no máximo 255 caracteres!',
            'descricao.min' => 'O campo "Subcategoria" deve ser no mínimo 3 caracteres!',
        ];
    }
}
