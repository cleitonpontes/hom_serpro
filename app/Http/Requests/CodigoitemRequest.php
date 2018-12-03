<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CodigoitemRequest extends FormRequest
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
            'descres' => 'required|max:10',
            'descricao' => 'required|min:5|max:255',
            'codigo_id' => 'required',
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
            'descricao.required' => 'O campo "Descrição" é obrigatório!',
            'descres.required' => 'O campo "Descrição Resumida" é obrigatório!',
            'descricao.min' => 'Descrição com mínimo de 05 caracteres!',
            'descricao.max' => 'Descrição com máximo de 255 caracteres!',
            'descres.max' => 'Descrição Resumida com máximo de 10 caracteres!',
            'codigo_id.required' => 'O campo "Visível" é obrigatório!',
        ];
    }
}
