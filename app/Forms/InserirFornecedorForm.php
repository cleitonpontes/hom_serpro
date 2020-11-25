<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class InserirFornecedorForm extends Form
{

    public function buildForm()
    {

        $this
            ->add('tipo_fornecedor', 'select', [
                'choices' => [
                    'FISICA' => 'Pessoa Física',
                    'JURIDICA' => 'Pessoa Jurídica',
                    'UG' => 'UG Siafi',
                    'IDGENERICO' => 'ID Genérico',
                ],
                'required ' => true,
                'selected' => 'en',
                'empty_value' => '=== Tipo Forncecedor ==='
            ])
            ->add('cpf_cnpj_idgener', 'text', [
                'label' => 'CPF/CNPJ/UG/ID Genérico',
                'rules' => 'required|min:1',
                'required ' => true,
                'attr' => [
                    'id' => 'esfera',
                    'maxlength' => 1,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
            ->add('nome', 'text', [
                'label' => 'Nome',
                'attr' => [
                    'id' => 'nome',
                    'maxlength' => 255,
                    'required ' => true,
//                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
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
