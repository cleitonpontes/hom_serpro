<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ExecsfsituacaoRequest extends FormRequest
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

        $execsfsituacao_id = '';

        if($this->aba == 'DESPESA_ANULAR'){
            $execsfsituacao_id = 'required';
        }

        return [
            'codigo' => "required|unique:execsfsituacao,codigo,{$id}",
            'descricao' => 'required',
            'aba' => 'required',
            'execsfsituacao_id' => $execsfsituacao_id,
            'status' => 'required'
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
            'codigo.required' => 'O campo "Código" é obrigatório!',
            'codigo.unique' => 'Este "Código" já está cadastrado!',
            'descricao.required' => 'O campo "Descriçao" é obrigatório!',
            'aba.required' => 'O campo "Aba" é obrigatório!',
            'execsfsituacao_id.required' => 'O campo "Anula Situação" é obrigatório!',
            'status.required' => 'O campo "Situação" é obrigatório!',
        ];
    }
}
