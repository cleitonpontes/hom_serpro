<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ContratoterceirizadoRequest extends FormRequest
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
             'contrato_id' => 'required',
             'cpf' => 'required|cpf',
             'nome' => 'required|min:5|max:255',
             'funcao_id' => 'required',
             'jornada' => 'required|numeric',
             'unidade' => 'required|max:255',
             'salario' => 'required',
             'custo' => 'required',
             'escolaridade_id' => 'required',
             'data_inicio' => 'required|date',
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
            //
        ];
    }
}
