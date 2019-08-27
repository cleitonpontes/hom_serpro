<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class FiltroRelatorioContratosForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('tipo_contrato', 'select', [
                'label' => 'Tipo Contrato',
                'attr' => [
                    'id' => 'tipo_contrato',
                ],
                'choices' => [
                    '1' => 'Contrato',
                    '2' => 'Empenho'
                ],

            ])
            ->add('numero', 'text', [
                'label' => 'Número',
                'attr' => [
                    'id' => 'numero',
                ]

            ])
            ->add('objeto', 'text', [
                'label' => 'Objeto',
                'attr' => [
                    'onkeyup' => "maiuscula(this)",
//                    'class' => 'col-md-6'
                ]
            ])->add('Filtrar', 'submit', [
                'label' => '<i class="fa fa-search"></i> Filtrar',
                'attr' => [
                    'class' => 'btn btn-success text-right'
                ]
            ]);
//            ->add('criacao', 'text', [
//                'label' => 'Criação (MM/AAAA)',
//                'rules' => "required|max:7"
//            ])
//            ->add('tipo', 'select', [
//                'label' => 'Tipo',
//                'rules' => "required",
//                'empty_value' => 'Selecione',
//                'choices' => [
//                    'AMBAS' => 'Ambas',
//                    'DESCONTO' => 'Desconto',
//                    'RENDIMENTO' => 'Rendimento'
//                ],
//            ])
//            ->add('situacao', 'select', [
//                'label' => 'Situação',
//                'rules' => "required",
//                'choices' => [
//                    'ATIVO' => 'Ativo',
//                    'INATIVO' => 'Inativo'
//                ],
//            ]);
    }
}
