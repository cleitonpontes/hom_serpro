<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class MinutaEmpenhoRequest extends FormRequest
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
        $this->data_ano = date('Y');

        return [
            'numero_empenho' => 'numeric|min:400001|max:800000',
            'descricao'=> 'required|max:468',
            'processo' => 'max:20',
            'data_emissao' => "date|after_or_equal:{$this->data_hoje}",

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
            'numero_empenho.min' => 'O :attribute deve maior que 400001',
            'numero_empenho.max' => 'O campo :attribute não pode ser maior que 800000',
            //'data_emissao.before' => "A :attribute deve ser uma data anterior a!",
        ];
    }
}
