<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ContratoocorrenciaRequest extends FormRequest
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
        $arquivos = (($this->arquivos[0])) ? 'mimetypes:application/pdf' : "";
        $novasituacao = '';
        $numeroocorrencia = '';
        $emailpreposto = '';

        if($this->situacao == 132){
            $novasituacao = 'required';
            $numeroocorrencia = 'required';
        }

        if($this->notificapreposto == true){
            $emailpreposto = 'required';
        }

        return [
             'situacao' => 'required',
             'novasituacao' => "{$novasituacao}",
             'numeroocorrencia' => "{$numeroocorrencia}",
             'data' => 'required|date',
             'ocorrencia' => 'required|min:20',
             'emailpreposto' => "{$emailpreposto}",
             'arquivos.*' => $arquivos,
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
            'situacao.required' => 'O campo Situação é obrigatório.',
            'novasituacao.required' => 'O campo Nova Situação é obrigatório.',
            'numeroocorrencia.required' => 'O campo Ocorrência Concluida é obrigatório.',
            'data.required' => 'O campo Data é obrigatório.',
            'ocorrencia.required' => 'O campo Ocorrência é obrigatório.',
            'ocorrencia.min' => 'O campo Ocorrência deve ter pelo menos 20 caracteres.',
            'emailpreposto.required' => 'O campo E-mail Preposto é obrigatório.',
        ];
    }
}
