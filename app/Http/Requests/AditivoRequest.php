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
        $data_limite = date('d/m/Y', strtotime('+50 year'));

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
            'vigencia_inicio' => 'required|date|before:vigencia_fim',
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$data_limite}",
            'valor_global' => 'required',
            'num_parcelas' => 'required',
            'valor_parcela' => 'required',
            'observacao' => 'required',
            'retroativo' => 'required',
            'retroativo_mesref_de' => 'required_if:retroativo,==,1',
            'retroativo_anoref_de' => 'required_if:retroativo,==,1',
            'retroativo_mesref_ate' => 'required_if:retroativo,==,1',
            'retroativo_anoref_ate' => 'required_if:retroativo,==,1',
            'retroativo_vencimento' => 'required_if:retroativo,==,1',
            'retroativo_valor' => 'required_if:retroativo,==,1', //ver com Schoolofnet como exigir que o valor seja maior que 0 quando tiver retroativo.
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
