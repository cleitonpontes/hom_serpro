<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class RhsituacaoRequest extends FormRequest
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
        $nd = $this->nd;
        $vpd = $this->vpd;
        $ddp_nivel = $this->ddp_nivel;

        return [
             'execsfsituacao_id' => [
                 'required',
                 (new Unique('rhsituacao','execsfsituacao_id'))
                     ->ignore($id)
                     ->where('nd',$nd)
                     ->where('vpd',$vpd)
                     ->where('ddp_nivel',$ddp_nivel)
             ],
             'nd' => 'required',
             'vpd' => 'required',
             'ddp_nivel' => 'required',
             'status' => 'required',
             'rhrubricas' => 'required',
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
            'execsfsituacao_id.required' => 'O campo "Situação Siafi" é obrigatório!',
            'execsfsituacao_id.unique' => 'Essa "RH - Situação" já está cadastrada!',
            'nd.required' => 'O campo "ND Detalhada" é obrigatório!',
            'vpd.required' => 'O campo "VPD" é obrigatório!',
            'ddp_nivel.required' => 'O campo "DDP Nível" é obrigatório!',
            'situacao.required' => 'O campo "Situação" é obrigatório!',
            'rhrubricas.required' => 'O campo "Rubrica" é obrigatório!',
        ];
    }
}
