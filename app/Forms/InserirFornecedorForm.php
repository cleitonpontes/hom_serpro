<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class InserirFornecedorForm extends Form
{

    public function buildForm()
    {

        $this
            ->add('esfera', 'text', [
                'label' => 'Esfera',
                'rules' => 'required|min:1',
                'attr' => [
                    'id' => 'esfera',
                    'maxlength' => 1,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
            ->add('ptrs', 'text', [
                'label' => 'PTRS',
                'required ' => true,
                'attr' => [
                    'id' => 'ptrs',
                    'maxlength' => 6,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
            ->add('fonte', 'text', [
                'label' => 'Fonte',
                'attr' => [
                    'id' => 'fonte',
                    'maxlength' => 10,
                    'required ' => true,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
            ->add('natureza_despesa', 'text', [
                'label' => 'Natureza de Despesa',
                'attr' => [
                    'id' => 'natureza_despesa',
                    'maxlength' => 6,
                    'required ' => true,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
            ->add('ugr', 'text', [
                'label' => 'UGR',
                'attr' => [
                    'id' => 'urg',
                    'maxlength' => 8,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
            ->add('plano_interno', 'text', [
                'label' => 'Plano Interno',
                'attr' => [
                    'id' => 'plano_interno',
                    'maxlength' => 11,
                    'required ' => true,
                    'oninput' => 'handleInput(event)'
                ]
            ])
//        ->add('valor', 'text',[
//                'label' => 'Valor',
//                'attr' => ['id'=>'valor', 'required ' => true,]
//            ])
            ->add('cancelar', 'submit', [
                'label' => '<i class="fa fa-reply"></i> Cancelar',
                'attr' => [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ]
            ])
            ->add('inserir', 'button', [
                'label' => '<i class="fa fa-save"></i> Salvar',
                'attr' => [
                    'class' => 'btn btn-success',
                    'id' => 'btn_inserir'
                ]
            ]);
    }
}
