<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Codigoitem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use App\Rules\NaoAceitarMinutaCompraDiferente;

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
        $unidadeorigem_id = $this->unidadeorigem_id ?? "NULL";
        $tipo_id = $this->tipo_id ?? "NULL";
        $this->data_limitefim = date('Y-m-d', strtotime('+50 year'));
        $this->data_limiteinicio = date('Y-m-d', strtotime('-50 year'));

        $this->data_atual = date('Y-m-d');
        $this->minutasempenho = $this->minutasempenho ?? [];

        return [
            'numero' => [
                'required',
                (new Unique('contratos', 'numero'))
                    ->ignore($id)
                    ->where('unidadeorigem_id', $unidadeorigem_id)
                    ->where('tipo_id',$tipo_id)
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

            'data_assinatura' => "required|date|after:{$this->data_limiteinicio}|before_or_equal:vigencia_inicio|before_or_equal:{$this->data_atual}",
            'data_publicacao' => "required|date|after:{$this->data_atual}",
            'vigencia_inicio' => 'required|date|after_or_equal:data_assinatura|before:vigencia_fim',
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$this->data_limitefim}",
            'valor_global' => 'required',
            'num_parcelas' => 'required',
            'valor_parcela' => 'required',
            'amparoslegais' => 'required',
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
        $data_limite = implode('/', array_reverse(explode('-', $this->data_limite)));
        $hoje = date('d/m/Y');
        $data_amanha = date('d/m/Y', strtotime('+1 day'));

        return [
            'vigencia_fim.before' => "A :attribute deve ser uma data anterior a {$data_limite}!",
            'data_assinatura.before_or_equal' => "A data da assinatura deve ser menor que  {$data_amanha} ",
            'data_publicacao.after' => "A data da publicação deve ser maior que {$hoje} ",
            'fornecedor_id.required' => 'O campo fornecedor é obrigatório',
            'data_assinatura.required' => 'O campo data da assinatura é obrigatório',
            'data_publicacao.required' => 'O campo data da publicação é obrigatório',
            'unidadecompra_id.required' => 'O campo unidade da compra é obrigatório',
            'modalidade_id.required' => 'O campo modalidade licitação é obrigatório',
            'amparoslegais.required' => 'O campo amparo legal é obrigatório',
            'licitacao_numero.required' => 'O campo número da licitação é obrigatório',
            'tipo_id.required' => 'O campo tipo é obrigatório',
            'categoria_id.required' => 'O campo categoria é obrigatório',
            'numero.required' => 'O campo número do contrato é obrigatório',
            'processo.required' => 'O campo número do processo é obrigatório',
            'unidadeorigem_id.required' => 'O campo unidade gestora origem é obrigatório',
            'vigencia_inicio.required' => 'O campo data de início da vigência é obrigatório',
            'vigencia_fim.required' => 'O campo data fim da vigência é obrigatório',
        ];
    }
}
