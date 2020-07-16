<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class SiasgcompraRequest extends FormRequest
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
        $unidade_id = $this->unidade_id ?? "NULL";
        $modalidade_id = $this->modalidade_id ?? "NULL";
        $ano = $this->ano ?? "NULL";

        return [
            'numero' => [
                'required',
                (new Unique('siasgcompras','numero'))
                    ->ignore($id)
                    ->where('unidade_id',$unidade_id)
                    ->where('modalidade_id',$modalidade_id)
                    ->where('ano',$ano)
            ],
            'unidade_id' => 'required',
            'modalidade_id' => 'required',
            'ano' => 'required',
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
            'numero' => 'Número Compra',
            'ano' => 'Ano Compra',
            'unidade_id' => 'Unidade da Compra',
            'modalidade_id' => 'Modalidade Licitação',
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
