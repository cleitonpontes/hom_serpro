<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class SubrogacaoRequest extends FormRequest
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
        $data_limite_inicio = date('Y-m-d', strtotime('-5 year'));
        $data_limite_fim = date('Y-m-d');

        return [
            'unidadeorigem_id' => "required",
            'contrato_id' => "required",
            'unidadedestino_id' => "required",
            'data_termo' => "required|date|after_or_equal:{$data_limite_inicio}|before_or_equal:{$data_limite_fim}",
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
            'unidadeorigem_id' => "Unidade Origem",
            'contrato_id' => "Contrato",
            'unidadedestino_id' => "Unidade Destino",
            'data_termo' => "Data Termo",
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
