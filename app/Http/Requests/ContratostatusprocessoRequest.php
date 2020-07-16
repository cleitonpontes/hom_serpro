<?php

namespace App\Http\Requests;

use App\Models\Codigoitem;


use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;


class ContratostatusprocessoRequest extends FormRequest
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
            'processo' => 'required',
            'data_inicio' => 'required|date',
            'data_fim' => Rule::requiredIf(function () {
                $codigoitem = Codigoitem::find($this->situacao);
                $descricaoCodigoItem = $codigoitem->descres;
                if($descricaoCodigoItem=="Finalizado"){return true;}
                else{return false;}
            }),
            'status' => 'required',
            'unidade' => 'required',
            'situacao' => 'required',
            'contrato_id' => 'required',
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
