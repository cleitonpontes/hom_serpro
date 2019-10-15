<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class FiltroRelatorioContratosForm extends Form
{
    public function buildForm()
    {

        $orgaos = $this->getData('orgaos') ?? [];
        $unidades = $this->getData('unidades') ?? [];
        $fornecedores = $this->getData('fornecedores') ?? [];
        $tipos = $this->getData('tipos') ?? [];
        $categorias = $this->getData('categorias') ?? [];


        $this
            ->add('orgao_id', 'select', [
                'label' => 'Órgão',
//                'rules' => "required",
                'attr' => [
                    'class' => 'form-control select2'
                ],
                'choices' => $orgaos,
                'empty_value' => 'Selecione',
            ])
            ->add('unidade_id', 'select', [
                'label' => 'Unidade Gestora',
//                'rules' => "required",
                'attr' => [
                    'class' => 'form-control select2'
                ],
                'choices' => $unidades,
                'empty_value' => 'Selecione',
            ])
            ->add('fornecedor_id', 'select', [
                'label' => 'Fornecedor',
//                'rules' => "required",
                'attr' => [
                    'class' => 'form-control select2'
                ],
                'choices' => $fornecedores,
                'empty_value' => 'Selecione',
            ])
            ->add('tipo_id', 'select', [
                'label' => 'Tipo Contrato',
                'attr' => [
                    'id' => 'tipo_contrato',
                ],
                'choices' => $tipos,
                'empty_value' => 'Selecione',
            ])
            ->add('categoria_id', 'select', [
                'label' => 'Categoria',
                'attr' => [
                    'id' => 'tipo_contrato',
                ],
                'choices' => $categorias,
                'empty_value' => 'Selecione',
            ])
            ->add('numero', 'text', [
                'label' => 'Número',
                'attr' => [
                    'id' => 'numero',
                ]

            ])
            ->add('processo', 'text', [
                'label' => 'Processo',
                'attr' => [
                    'id' => 'processo',
                ]

            ])
            ->add('objeto', 'text', [
                'label' => 'Objeto',
                'attr' => [
                    'onkeyup' => "maiuscula(this)",
//                    'class' => 'col-md-6'
                ]
            ])
            ->add('info_complementar', 'text', [
                'label' => 'Inf. Complementar',
                'attr' => [
                    'onkeyup' => "maiuscula(this)",
//                    'class' => 'col-md-6'
                ]
            ])


            ->add('Filtrar', 'submit', [
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
