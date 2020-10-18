<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\ValorMeta;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\Formatador;

class ContratoItemServicoIndicadorRequest extends FormRequest
{
    use Formatador;

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
            'contratoitem_servico_id' => 'required',
            'indicador_id' => 'required',
            'periodicidade_id' => 'required',
            'tipo_afericao' => 'required',
            'vlrmeta' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($this->tipo_afericao === '0' && $this->retornaFormatoAmericano($value) > 100) {
                        $fail('Para a aferição percentual, a meta deve ser no máximo 100');
                    }
                },
            ],

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
