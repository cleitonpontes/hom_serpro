<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Codigoitem;
use App\Rules\NaoAceitarFeriado;
use App\Rules\NaoAceitarFimDeSemana;
use Illuminate\Foundation\Http\FormRequest;


class RescisaoRequest extends FormRequest
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
        $tipo_id = $this->tipo_id ?? "NULL";
        $this->data_atual = date('Y-m-d');
        $this->data_limitefim = date('Y-m-d', strtotime('+50 year'));
        $this->data_limiteinicio = date('Y-m-d', strtotime('-50 year'));

        $rules = [
            'observacao' => 'required',
            'processo' => 'required',
            'data_assinatura' => "required|date|after:{$this->data_limiteinicio}|after_or_equal:vigencia_inicio",
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$this->data_limitefim}",
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
        $data_limite = implode('/',array_reverse(explode('-',$this->data_limite)));

        return [
            'data_publicacao.after' => "A :attribute deve ser igual ou posterior a Data da Assinatura!",
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
                "after_or_equal:data_assinatura",
                new NaoAceitarFeriado(),
                new NaoAceitarFimDeSemana()
            ];
        }
        return $retorno;
    }
}
