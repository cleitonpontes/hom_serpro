<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\NaoAceitarEstrangeiro;
use App\Rules\NaoAceitarValorMaiorTotal;
use App\Rules\NaoAceitarZero;
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
        return [];
//        dd($this->valor_total_item);
        return [
            'credito' => 'gt:valor_utilizado',
            'valor_total.*' => [
                'filled',
                new NaoAceitarZero(),
                new NaoAceitarValorMaiorTotal($this->valor_total_item)
            ],
            'qtd.*' => [
                'filled',
                new NaoAceitarZero()
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
            'credito.gt' => 'O saldo não pode ser negativo.',
            'valor_total.*.filled' => 'O campo :attribute não pode estar vazio.',
        ];
    }
}
