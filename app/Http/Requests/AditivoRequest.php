<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class AditivoRequest extends FormRequest
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
        $contrato_id = $this->contrato_id ?? "NULL";
        $tipo_id = $this->tipo_id ?? "NULL";

        return [
            'numero' => [
                'required',
                (new Unique('contratohistorico','numero'))
                    ->ignore($id)
                    ->where('contrato_id',$contrato_id)
                    ->where('tipo_id',$tipo_id)
            ],
            'fornecedor_id' => 'required',
            'contrato_id' => 'required',
            'tipo_id' => 'required',
            'unidade_id' => 'required',
            'data_assinatura' => 'required|date',
            'data_publicacao' => 'required|date',
            'vigencia_inicio' => 'required|date',
            'vigencia_fim' => 'required|date',
            'valor_global' => 'required',
            'num_parcelas' => 'required',
            'valor_parcela' => 'required',
            'observacao' => 'required',
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
