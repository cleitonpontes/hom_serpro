<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ContratoresponsavelRequest extends FormRequest
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
        $this->data_hoje = date('Y-m-d');
        $this->data_limiteinicio = date('Y-m-d', strtotime('-50 year'));

        //'data inicio' deve estar entre hoje e 50 anos no passado
        $validacao_data_inicio = ($this->data_inicio) ? "date|after_or_equal:{$this->data_limiteinicio}|before_or_equal:{$this->data_hoje}" : "" ;
        //'data fim' deve estar entre hoje e a 'data inicio'
        $validacao_data_fim = ($this->data_fim) ? "date|after:data_inicio|before_or_equal:{$this->data_hoje}" : "" ;

        return [
            'contrato_id' => 'required',
            'user_id' => 'required',
            'funcao_id' => 'required',
            'portaria' => 'required|max:255',
            'data_inicio' => "required|{$validacao_data_inicio}",
            'data_fim' => "required_if:situacao,0|{$validacao_data_fim}",
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
            'situacao' => 'situação',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        $data_limite = implode('/',array_reverse(explode('-',$this->data_limiteinicio)));
        $data_hoje = implode('/',array_reverse(explode('-',$this->data_hoje)));
        return [
            'data_inicio.after_or_equal' => "O campo :attribute deve ser uma data posterior ou igual a {$data_limite}",
            'data_inicio.before_or_equal' => "O campo :attribute deve ser uma data anterior ou igual a {$data_hoje}",
            'data_fim.after' => "O campo :attribute deve ser uma data posterior a data inicio",
            'data_fim.before_or_equal' => "O campo :attribute deve ser uma data anterior ou igual a {$data_hoje}",
            'data_fim.required_if' => "O campo data fim é obrigatorio quando a situação for inativa"
        ];
    }
}
