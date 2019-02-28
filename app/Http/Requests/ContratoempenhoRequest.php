<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class ContratoempenhoRequest extends FormRequest
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
        $empenho_id = $this->empenho_id ?? "NULL";

        return [
            'contrato_id' => [
                'required',
                (new Unique('contratoempenhos','contrato_id'))
                    ->ignore($id)
                    ->where('empenho_id',$empenho_id)
            ],
            'fornecedor_id' => 'required',
            'empenho_id' => 'required',
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
            'contrato_id.required' => 'O campo "Contrato" é obrigatório!',
            'contrato_id.unique' => 'Este Empenho já foi adicionado!',
            'fornecedor_id.required' => 'O campo "Favorecido" é obrigatório!',
            'empenho_id.required' => 'O campo "Empenho" é obrigatório!',
        ];
    }
}
