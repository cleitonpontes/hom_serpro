<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class MudarUgForm extends Form
{
    public function buildForm()
    {
        // caso seja adm vamos abrir o campo texto para entrar com o código da UG - mvascs@gmail.com
        if (backpack_user()->hasRole('Administrador')) {
            $ugs = $this->getData('ugs') ?? "NULL";
            $this
            ->add('ug', 'text', [
                'label' => 'Informe o código da UG/UASG',
                'rules' => 'required|max:6',
                'attr' => [
                    // 'onkeyup' => "maiuscula(this)"
                ],
            ]);
        } else {
            $ugs = $this->getData('ugs') ?? "NULL";
            $this
                ->add('ug', 'select', [
                    'label' => 'UG/UASG',
    //                'rules' => "required",
                    'attr' => [
    //                    'class' => 'select2'
                    ],
                    'choices' => $ugs,
                    'empty_value' => 'Selecione',
                ]);
        }
    }
}
