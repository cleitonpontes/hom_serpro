<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class MeusdadosForm extends Form
{
    public function buildForm()
    {
        $id = $this->getData('users') ?? "NULL";
        $senhasiafi = $this->getData('senhasiafi') ?? "NULL";
        $ugprimaria = $this->getData('ugprimaria') ?? "NULL";

        $this
            ->add('cpf', 'text', [
                'label' => 'CPF',
                'attr' => [
                    'disabled' => true
                ]
            ])
            ->add('name', 'text', [
                'label' => 'Nome',
                'rules' => 'required|max:255',
                'attr' => [
                    'onkeyup' => "maiuscula(this)"
                ]
            ])
            ->add('email', 'email', [
                'label' => 'E-mail',
                'rules' => "required|max:255|email|unique:users,email,{$id}"
            ])
            ->add('ugprimaria', 'select', [
                'label' => 'UG Primária',
                'attr' => [
//                    'class' => 'select',
                    'disabled' => true
                ],
                'choices' => $ugprimaria,
            ])
            ->add('senhasiafi', 'password', [
                'label' => 'Senha SIAFI',
                'value' => base64_decode($senhasiafi)
            ]);
    }
}
