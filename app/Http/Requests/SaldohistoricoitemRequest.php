<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class SaldohistoricoitemRequest extends FormRequest
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
        $saldoable_type = $this->saldoable_type ?? "NULL";
        $saldoable_id = $this->saldoable_id ?? "NULL";

        return [
            'contratoitem_id' => [
                'required',
                (new Unique('saldohistoricoitens','contratoitem_id'))
                    ->ignore($id)
                    ->where('saldoable_type',$saldoable_type)
                    ->where('saldoable_id',$saldoable_id)
                ->whereNull('deleted_at')
            ],
            'quantidade' => 'required',
            'valorunitario' => 'required',
            'valortotal' => 'required',
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
            'contratoitem_id' => 'Item',
            'quantidade' => 'Quantidade',
            'valorunitario' => 'Valor Unitário',
            'valortotal' => 'Valor Total',
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
            'contratoitem_id.unique' => 'Este Item já está cadastrado para este Contrato Histórico!',
        ];
    }
}
