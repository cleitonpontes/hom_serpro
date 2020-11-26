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
                'empty_value' => '=== Tipo Forncecedor ===',
                'attr' => [
                    'id' => 'tipo_fornecedor'
                ]
            ])
            ->add('cpf_cnpj_idgener', 'text', [
                'label' => 'CPF/CNPJ/UG/ID Genérico',
                'rules' => 'required|min:1',
                'required ' => true,
                'attr' => [
                    'id' => 'cpf_cnpj_idgener',
                ]
            ])
            ->add('nome', 'text', [
                'label' => 'Nome',
                'attr' => [
                    'id' => 'nome',
                    'maxlength' => 255,
                    'required ' => true,
                    'onkeyup' => "maiuscula(this)"
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
