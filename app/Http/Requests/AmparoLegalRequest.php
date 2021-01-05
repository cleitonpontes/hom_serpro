<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;


class AmparoLegalRequest extends FormRequest
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
    public function rules():array
    {
        return [
            'codigo' => 'required',
            'modalidade_id' => 'required',
        ];
        // return [
        //     //  'nome' => 'required|max:255|unique:indicadores,nome,NULL,NULL,deleted_at,NULL',
        //     //  'finalidade' => 'required',
        //     //  'situacao' => 'required',

        // ];
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
            //
            'codigo.required' => 'O campo "Código" é obrigatório!',
            'modalidade_id.required' => 'O campo "Modalidade" é obrigatório!',
        ];
    }
}
