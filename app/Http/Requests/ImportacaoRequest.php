<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Codigoitem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ImportacaoRequest extends FormRequest
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
        $nome_arquivo = $this->nome_arquivo ?? "NULL";

        $arquivos = ($this->arquivos) ? '|mimetypes:text/plain,text/csv' : "NULL";

        return [
            'nome_arquivo' => 'required|max:255',
            'unidade_id' => [
                'required',
                (new Unique('importacoes','unidade_id'))
                    ->ignore($id)
                    ->where('nome_arquivo',$nome_arquivo)
            ],
            'tipo_id' => 'required',
            'contrato_id' => Rule::requiredIf(function () {
                $tipo = Codigoitem::find($this->tipo_id);
                if(isset($tipo->descricao)){
                    if($tipo->descricao == 'Terceirizado'){
                        return true;
                    }
                }
                return false;
            }),
            'role_id' => Rule::requiredIf(function () {
                $tipo = Codigoitem::find($this->tipo_id);
                if(isset($tipo->descricao)){
                    if($tipo->descricao == 'Usuários'){
                        return true;
                    }
                }
                return false;
            }),
            'situacao_id' => 'required',
            'delimitador' => 'required|max:1' ,
            'arquivos.*' => 'required'.$arquivos,
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
            'nome_arquivo' => 'Nome do Arquivo',
            'tipo_id' => 'Tipo',
            'unidade_id' => 'Unidade Gestora',
            'contrato_id' => 'Contrato',
            'delimitador' => 'Delimitador' ,
            'arquivos.*' => 'Arquivos',
            'situacao_id' => 'Situação',
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
            'delimitador.max' => "O campo :attribute não pode ser superior a 1 caractere.",

        ];
    }
}
