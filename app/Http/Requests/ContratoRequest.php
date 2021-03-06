<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Http\Traits\RegrasDataPublicacao;
use App\Models\Codigoitem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use App\Rules\NaoAceitarMinutaCompraDiferente;

class ContratoRequest extends FormRequest
{
    use RegrasDataPublicacao;
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
        $this->data_limitefim = date('Y-m-d', strtotime('+50 year'));
        $this->data_limiteinicio = date('Y-m-d', strtotime('-50 year'));

        $this->data_atual = date('Y-m-d');
        $this->minutasempenho = $this->minutasempenho ?? [];

        $rules = [
            'numero' => [
                'required',
                (new Unique('contratos', 'numero'))
                    ->ignore($id)
                    ->where('unidadeorigem_id', $unidadeorigem_id)
                    ->where('tipo_id', $tipo_id)
            ],
            'fornecedor_id' => 'required',
            'minutasempenho' => new NaoAceitarMinutaCompraDiferente,
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
                if (isset($modalidade->descricao)) {
                    if (in_array($modalidade->descricao, config('app.modalidades_sem_exigencia'))) {
                        return false;
                    }
                }
                return true;
            }),
            'unidadecompra_id' => Rule::requiredIf(function () {
                $modalidade = Codigoitem::find($this->modalidade_id);
                if (isset($modalidade->descricao)) {
                    if (in_array($modalidade->descricao, config('app.modalidades_sem_exigencia'))) {
                        return false;
                    }
                }
                return true;
            }),

            'data_assinatura' => "required|date|after:{$this->data_limiteinicio}|before_or_equal:{$this->data_atual}",
            'vigencia_inicio' => 'required|date|after_or_equal:data_assinatura|before:vigencia_fim',
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$this->data_limitefim}",
            'valor_global' => 'required',
            'num_parcelas' => 'required',
            'valor_parcela' => 'required',
            'amparoslegais' => 'required',
//            'arquivos' => 'file|mimes:pdf',
            'situacao' => 'required',
        ];
        $rules['data_publicacao'] = $this->ruleDataPublicacao($tipo_id, $this->id);

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
        $data_limite = implode('/', array_reverse(explode('-', $this->data_limite)));
        $hoje = date('d/m/Y');

        return [
            'vigencia_fim.before' => "A :attribute deve ser uma data anterior a {$data_limite}!",
            'data_assinatura.before_or_equal' => "A data da assinatura deve ser menor que a Vig??ncia in??cio.",
            'data_publicacao.after' => "A data da publica????o deve ser maior que {$hoje}.",
            'fornecedor_id.required' => 'O campo fornecedor ?? obrigat??rio.',
            'data_assinatura.required' => 'O campo data da assinatura ?? obrigat??rio.',
            'data_publicacao.required' => 'O campo data da publica????o ?? obrigat??rio.',
            'unidadecompra_id.required' => 'O campo unidade da compra ?? obrigat??rio.',
            'modalidade_id.required' => 'O campo modalidade licita????o ?? obrigat??rio.',
            'amparoslegais.required' => 'O campo amparo legal ?? obrigat??rio.',
            'licitacao_numero.required' => 'O campo n??mero da licita????o ?? obrigat??rio.',
            'tipo_id.required' => 'O campo tipo ?? obrigat??rio.',
            'categoria_id.required' => 'O campo categoria ?? obrigat??rio.',
            'numero.required' => 'O campo n??mero do contrato ?? obrigat??rio.',
            'numero.unique' => 'O n??mero do contrato j?? est?? sendo utilizado.',
            'processo.required' => 'O campo n??mero do processo ?? obrigat??rio.',
            'unidadeorigem_id.required' => 'O campo unidade gestora origem ?? obrigat??rio.',
            'vigencia_inicio.required' => 'O campo data de in??cio da vig??ncia ?? obrigat??rio.',
            'vigencia_fim.required' => 'O campo data fim da vig??ncia ?? obrigat??rio.',
            'valor_parcela.required' => 'O campo valor da parcela ?? obrigat??rio.',
        ];
    }
}
