<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Codigoitem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ContratoRequest extends FormRequest
{
    protected $data_limite;

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
        $this->data_limitefim = date('Y-m-d', strtotime('+50 year'));
        $this->data_limiteinicio = date('Y-m-d', strtotime('-50 year'));

        $data_publicacao = ($this->data_publicacao) ? "date|after:{$this->data_limiteinicio}|after_or_equal:data_assinatura" : "" ;


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
                $modalidade = Codigoitem::find($this->modalidade_id);
                if(in_array($modalidade->descricao,config('app.modalidades_sem_exigencia'))){
                    return false;
                }
                return true;
            }),
            'data_assinatura' => "required|date|after:{$this->data_limiteinicio}|before_or_equal:vigencia_inicio",
            'data_publicacao' => $data_publicacao,
            'vigencia_inicio' => 'required|date|after_or_equal:data_assinatura|before:vigencia_fim',
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$this->data_limitefim}",
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
        $data_limite = implode('/',array_reverse(explode('-',$this->data_limite)));

        return [
            'vigencia_fim.before' => "A :attribute deve ser uma data anterior a {$data_limite}!",
        ];
    }
}
