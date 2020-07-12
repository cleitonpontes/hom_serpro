<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Codigoitem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class SiasgcontratoRequest extends FormRequest
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
        $unidade_id = $this->unidade_id ?? "NULL";
        $tipo_id = $this->tipo_id ?? "NULL";
        $ano = $this->ano ?? "NULL";

        return [
            'numero' => [
                'required',
                (new Unique('siasgcontratos', 'numero'))
                    ->ignore($id)
                    ->where('unidade_id', $unidade_id)
                    ->where('tipo_id', $tipo_id)
                    ->where('ano', $ano)
            ],
            'unidade_id' => 'required',
            'tipo_id' => 'required',
            'ano' => 'required',
            'codigo_interno' => 'required_if:sisg,0'
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
            'numero' => 'Número',
            'ano' => 'Ano',
            'unidade_id' => 'Unidade do contrato',
            'tipo_id' => 'Tipo',
            'codigo_interno' => 'Código interno',
            'sisg' => 'Rotina SISG',
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
            'codigo_interno.required_if' => "O campo Código interno é obrigatório quando Rotina SISG for Não",
        ];
    }
}
