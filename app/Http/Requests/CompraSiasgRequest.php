<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CompraSiasgRequest extends FormRequest
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
//             'descricao' => 'required|min:5|max:255',
//             'visivel' => 'required',
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
//            'descricao.required' => 'O campo "Descrição" é obrigatório!',
//            'descricao.min' => 'Descrição com mínimo de 05 caracteres!',
//            'descricao.max' => 'Descrição com máximo de 255 caracteres!',
//            'visivel.required' => 'O campo "Visível" é obrigatório!',
        ];
    }
}
