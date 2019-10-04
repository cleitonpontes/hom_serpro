<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ContratoRequest extends FormRequest
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
        $data_limite = date('d/m/Y', strtotime('+50 year'));

        return [
//            'numero' => [
//                'required',
//                (new Unique('contratos','numero'))
//                    ->ignore($id)
//                    ->where('unidade_id',$unidade_id)
//            ],
            'numero' => 'required',
            'fornecedor_id' => 'required',
            'tipo_id' => 'required',
            'categoria_id' => 'required',
            'receita_despesa' => 'required',
            'unidade_id' => 'required',
            'processo' => 'required',
            'objeto' => 'required',
            'modalidade_id' => 'required',
            'licitacao_numero' => Rule::requiredIf(function () {
                if($this->modalidade_id == '75'){
                    return false;
                }

                return true;
            }),
            'data_assinatura' => 'required|date|before_or_equal:vigencia_inicio',
            'vigencia_inicio' => 'required|date|after_or_equal:data_assinatura|before:vigencia_fim',
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$data_limite}",
            'valor_global' => 'required',
            'num_parcelas' => 'required',
            'valor_parcela' => 'required',
//            'arquivos' => 'file|mimes:pdf',
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
