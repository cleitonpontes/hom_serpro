<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class AditivoRequest extends FormRequest
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
        $contrato_id = $this->contrato_id ?? "NULL";
        $this->data_limite = date('Y-m-d', strtotime('+50 year'));

        $this->hoje = date('Y-m-d');
        $this->data_amanha = date('Y-m-d', strtotime('+1 day'));

        return [
            'numero' => [
                'required',
                (new Unique('contratohistorico','numero'))
                    ->where('contrato_id',$contrato_id)
            ],
            'fornecedor_id' => 'required',
            'contrato_id' => 'required',
            'unidade_id' => 'required',
            'data_assinatura' => "required|date|before:{$this->data_amanha}",
            'data_publicacao' => "required|date|after:{$this->hoje}",
            'vigencia_inicio' => 'required|date|before:vigencia_fim',
            'vigencia_fim' => "required|date|after:vigencia_inicio|before:{$this->data_limite}",
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
        $data_limite = implode('/',array_reverse(explode('-',$this->data_limite)));
        $hoje = date('d/m/Y');
        $data_amanha = date('d/m/Y', strtotime('+1 day'));


        return [
            'vigencia_fim.before' => "A :attribute deve ser uma data anterior a {$data_limite}!",
            'data_publicacao.after' => "A data da publicação deve ser maior que {$hoje} ",
            'data_assinatura.before' => "A data da assinatura deve ser menor que  {$data_amanha} "
        ];
    }
}
