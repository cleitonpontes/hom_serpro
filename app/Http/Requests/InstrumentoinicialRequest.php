<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Codigoitem;
use App\Rules\NaoAceitarFeriado;
use App\Rules\NaoAceitarFimDeSemana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class InstrumentoinicialRequest extends FormRequest
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
        $unidadeorigem_id = $this->unidadeorigem_id ?? "NULL";
        $tipo_id = $this->tipo_id ?? "NULL";
        $this->data_atual = date('Y-m-d');
        $this->data_limitefim = date('Y-m-d', strtotime('+50 year'));
        $this->data_limiteinicio = date('Y-m-d', strtotime('-50 year'));

        $rules = [
            'numero' => [
                'required',
                (new Unique('contratohistorico', 'numero'))
                    ->ignore($id)
                    ->where('unidadeorigem_id', $unidadeorigem_id)
                    ->where('tipo_id',$tipo_id)
            ],
//            'numero' => 'required',
            'fornecedor_id' => 'required',
            'tipo_id' => 'required',
            'categoria_id' => 'required',
            'receita_despesa' => 'required',
            'unidade_id' => 'required',
            'unidadeorigem_id' => 'required',
            'processo' => 'required',
            'objeto' => 'required',
            'modalidade_id' => 'required',
            'licitacao_numero' => Rule::requiredIf(function () {
                $modalidade = Codigoitem::find($this->modalidade_id);
                if(isset($modalidade->descricao)){
                    if(in_array($modalidade->descricao,config('app.modalidades_sem_exigencia'))){
                        return false;
                    }
                }
                return true;
            }),
            'unidadecompra_id' => Rule::requiredIf(function () {
                $modalidade = Codigoitem::find($this->modalidade_id);
                if(isset($modalidade->descricao)){
                    if(in_array($modalidade->descricao,config('app.modalidades_sem_exigencia'))){
                        return false;
                    }
                }
                return true;
            }),
            'data_assinatura' => "required|date|after:{$this->data_limiteinicio}|before_or_equal:vigencia_inicio",
            'vigencia_inicio' => 'required|date|after_or_equal:data_assinatura|before:vigencia_fim',
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$this->data_limitefim}",
            'valor_global' => 'required',
            'num_parcelas' => 'required',
            'valor_parcela' => 'required',
//            'arquivos' => 'file|mimes:pdf',
            'situacao' => 'required',
        ];

        $rules['data_publicacao'] = $this->ruleDataPublicacao($tipo_id);

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'data_assinatura' => "Data assinatura"
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {

        $data_limite = implode('/',array_reverse(explode('-',$this->data_limitefim)));

        return [
            'vigencia_fim.before' => "A :attribute deve ser uma data anterior a {$data_limite}!",
        ];
    }

    private function ruleDataPublicacao ($tipo_id = null)
    {
        $arrCodigoItens = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Outros')
            ->where('descricao', '<>', 'Empenho')
            ->orderBy('descricao')
            ->pluck('id')
            ->toArray();

        $retorno = [
            'required',
            'date'
        ];

        if (in_array($tipo_id, $arrCodigoItens)) {
            $retorno = [
                'required',
                'date',
                "after:{$this->data_atual}",
                "after:data_assinatura",
                new NaoAceitarFeriado(),
                new NaoAceitarFimDeSemana()
            ];
        }
        return $retorno;
    }
}
