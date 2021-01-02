<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Codigoitem;
use App\Rules\NaoAceitarFeriado;
use App\Rules\NaoAceitarFimDeSemana;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class ApostilamentoRequest extends FormRequest
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

        $this->hoje = date('Y-m-d');

        $rules = [
            'numero' => [
                'required',
                (new Unique('contratohistorico','numero'))
                    ->ignore($id)
                    ->where('contrato_id',$contrato_id)
                    ->where('tipo_id',$tipo_id)
            ],
            'contrato_id' => 'required',
            'unidade_id' => 'required',
            'data_assinatura' => "required|date|before_or_equal:{$this->hoje}",
            'data_inicio_novo_valor' => 'required|date|after_or_equal:data_assinatura',
            'novo_num_parcelas' => 'required',
            'novo_valor_parcela' => 'required',
            'novo_valor_global' => 'required',
            'observacao' => 'required',
            'retroativo' => 'required',
            'retroativo_mesref_de' => 'required_if:retroativo,==,1',
            'retroativo_anoref_de' => 'required_if:retroativo,==,1',
            'retroativo_mesref_ate' => 'required_if:retroativo,==,1',
            'retroativo_anoref_ate' => 'required_if:retroativo,==,1',
            'retroativo_vencimento' => 'required_if:retroativo,==,1',
            'retroativo_valor' => 'required_if:retroativo,==,1', //ver com Schoolofnet como exigir que o valor seja maior que 0 quando tiver retroativo.
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
                "after:{$this->hoje}",
                "after_or_equal:data_assinatura",
                new NaoAceitarFeriado(),
                new NaoAceitarFimDeSemana()
            ];
        }
        return $retorno;
    }
}
