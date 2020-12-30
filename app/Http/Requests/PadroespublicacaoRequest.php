<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class PadroespublicacaoRequest extends FormRequest
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
        $tipo_mudanca_id = $this->tipo_mudanca_id ?? "NULL";

        return [
            'tipo_contrato_id' => [
                'required',
                (new Unique('padroespublicacao', 'tipo_contrato_id'))
                    ->ignore($id)
                    ->where('tipo_mudanca_id', $tipo_mudanca_id)
            ],
            'tipo_mudanca_id' => 'required',
            'texto_padrao' => 'required',
            'identificador_norma_id' => 'required',
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
            'tipo_contrato_id' => 'Tipo Instrumento',
            'tipo_mudanca_id' => 'Tipo Mudança',
            'identificador_norma_id' => 'Identificador Norma',
            'texto_padrao' => 'Texto Padrão',
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
