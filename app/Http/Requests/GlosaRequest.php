<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\NaoRepetirFaixa;
use App\Rules\NaoRepetirFaixaSlider;
use App\Rules\ValorMaximoFaixaAjuste;
use App\Rules\ValorMaximoFaixaAjusteSlider;
use App\Rules\AteMaiorQueAPartir;
use Illuminate\Foundation\Http\FormRequest;

class GlosaRequest extends FormRequest
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
        return [
            'valor_glosa' => 'required',
            'escopo_id' => 'required',
            'to' => [
                new AteMaiorQueAPartir($this->from),
                new ValorMaximoFaixaAjuste($this->vlrmeta),
                new NaoRepetirFaixa($this->contratoitem_servico_indicador_id, $this->from)
            ],
            'slider' => [
                new ValorMaximoFaixaAjusteSlider($this->vlrmeta),
                new NaoRepetirFaixaSlider($this->contratoitem_servico_indicador_id)
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
