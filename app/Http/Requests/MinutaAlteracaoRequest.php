<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\NaoAceitarEstrangeiro;
use App\Rules\NaoAceitarValorMaiorTotal;
use App\Rules\NaoAceitarZero;
use App\Rules\NaoAceitarFloatParaSuprimentoESisrp;
use Illuminate\Foundation\Http\FormRequest;

class MinutaAlteracaoRequest extends FormRequest
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
        $valor_utilizado = number_format($this->valor_utilizado, 2, '.', '');

        return [
            'credito' => 'gte:'.$valor_utilizado,
            'valor_total.*' => [
                'filled',
                new NaoAceitarZero($this->tipo_alteracao),
                new NaoAceitarValorMaiorTotal(
                    $this->tipo_alteracao,
                    $this->valor_total_item,
                    $this->vlr_total_item,
                    $this->tipo_empenho_por
                )
            ],
            'qtd.*' => [
                'filled',
                new NaoAceitarZero($this->tipo_alteracao),
                new NaoAceitarFloatParaSuprimentoESisrp($this)
            ]
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
            'valor_total.*' => 'Valor Total',
            'qtd.*' => 'Qtd'
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
            'credito.gte' => 'O saldo não pode ser negativo.',
            'valor_total.*.filled' => 'O campo :attribute não pode estar vazio.',
        ];
    }
}
