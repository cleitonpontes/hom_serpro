<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class TransparenciaIndexForm extends Form
{
    public function buildForm()
    {
//        $orgaos = $this->getData('orgaos') ?? [];
//        $unidades = $this->getData('unidades') ?? [];
//        $fornecedores = $this->getData('fornecedores') ?? [];
//        $contratos = $this->getData('contratos') ?? [];

        $this
            ->add('orgao', 'select', [
                'label' => 'Órgão',
//                'rules' => "required",
                'attr' => [
                    'class' => 'form-control select2'
                ],
//                'choices' => $orgaos,
//                'empty_value' => 'Todos',
            ])
            ->add('unidade', 'select', [
                'label' => 'Unidade Gestora',
//                'rules' => "required",
                'attr' => [
                    'class' => 'form-control select2'
                ],
//                'choices' => $unidades,
//                'empty_value' => 'Todos',
            ])
            ->add('fornecedor', 'select', [
                'label' => 'Fornecedor',
//                'rules' => "required",
                'attr' => [
                    'class' => 'form-control select2'
                ],
//                'choices' => $fornecedores,
//                'empty_value' => 'Todos',
            ])
            ->add('contrato', 'select', [
                'label' => 'Contrato',
//                'rules' => "required",
                'attr' => [
                    'class' => 'form-control select2'
                ],
//                'choices' => $fornecedores,
//                'empty_value' => 'Todos',
            ])
            ->add('Filtrar', 'submit', [
                'label' => '<i class="fa fa-search"></i> Filtrar',
                'attr' => [
                    'class' => 'btn btn-success text-right'
                ]
            ]);

    }
}
