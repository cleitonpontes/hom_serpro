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
             'ocorrencia' => 'required',
             'emailpreposto' => "{$emailpreposto}",
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
