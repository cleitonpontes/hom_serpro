<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Unique;

class UnidadeconfiguracaoRequest extends FormRequest
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
        $user2_id = $this->user2_id ?? null;
        $user3_id = $this->user3_id ?? null;
        $user4_id = $this->user4_id ?? null;

        $role_user2 = [];
        if($user2_id!=null){
            if($user2_id and $user3_id){
                $role_user2 = [
                    'user2_id' => 'different:user1_id,user3_id',
                ];
            }elseif ($user2_id and $user3_id and $user4_id){
                $role_user2 = [
                    'user2_id' => 'different:user1_id,user3_id,user4_id',
                ];
            }else{
                $role_user2 = [
                    'user2_id' => 'different:user1_id',
                ];
            }
        }

        $role_user3 = [];
        if($user3_id!=null){
            if($user2_id and $user3_id){
                $role_user3 = [
                    'user3_id' => 'different:user1_id,user2_id',
                ];
            }elseif ($user2_id and $user3_id and $user4_id){
                $role_user3 = [
                    'user3_id' => 'different:user1_id,user2_id,user4_id',
                ];
            }else{
                $role_user3 = [
                    'user3_id' => 'different:user1_id',
                ];
            }
        }

        $role_user4 = [];
        if($user4_id!=null){
            if($user2_id and $user4_id){
                $role_user4 = [
                    'user4_id' => 'different:user1_id,user2_id',
                ];
            }elseif ($user2_id and $user3_id and $user4_id){
                $role_user4 = [
                    'user4_id' => 'different:user1_id,user2_id,user3_id',
                ];
            }else{
                $role_user4 = [
                    'user4_id' => 'different:user1_id',
                ];
            }
        }



        $rule = [
            'unidade_id' => "required|unique:unidadeconfiguracao,unidade_id,{$id}",
            'user1_id' => 'required',
            'telefone1' => 'required',
            'email_diario_periodicidade' => 'required_if:email_diario,1',
            'email_mensal_dia' => 'required_if:email_mensal,1|numeric|min:1|max:20',
            'padrao_processo_mascara' => "required",
        ];


        $rules = array_merge($role_user2,$role_user3,$role_user4,$rule);


        return $rules;
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
            'unidade_id.required' => 'O campo "Unidade" ?? obrigat??rio!',
            'unidade_id.unique' => 'Esta Unidade j?? tem configura????o cadastrada!',
            'user1_id.required' => 'O campo "Chefe Contratos" ?? obrigat??rio!',
            'email_diario_periodicidade.required_if' => 'O campo "Periodicidade E-mails" ?? obrigat??rio se "Rotina Di??ria E-mail" for "Sim"!',
            'email_mensal_dia.required_if' => 'O campo "Envia Extrato que dia do M??s?" ?? obrigat??rio se "Extrato Mensal" for "Sim"!',
            'padrao_processo_marcara.required' => "O campo Padr??o Processo M??scara ?? obrigat??rio!",
        ];
    }
}
