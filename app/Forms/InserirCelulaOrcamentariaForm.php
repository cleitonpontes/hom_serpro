<?php
namespace App\Forms;

use App\Models\Codigoitem;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\Form;

class InserirCelulaOrcamentariaForm extends Form
{

    public function buildForm()
    {

        $this
        ->add('esfera', 'text',[
            'label' => 'Esfera',
                'attr' => [
                    'id'=>'esfera',
                    'maxlength' => 1,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
        ->add('ptrs','text',[
            'label' => 'PTRS',
                'attr' => [
                    'id'=>'ptrs',
                    'maxlength' => 6,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
        ->add('fonte', 'text',[
            'label' => 'Fonte',
                'attr' => [
                    'id'=>'fonte',
                    'maxlength' => 10,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
        ->add('natureza_despesa', 'text',[
            'label' => 'Natureza de Despesa',
                'attr' => [
                    'id'=>'natureza_despesa',
                    'maxlength' => 6,
                    'onkeypress' => 'return somenteNumeros(event)'
                    ]
            ])
        ->add('ugr', 'text',[
            'label' => 'UGR',
                'attr' => [
                    'id'=>'urg',
                    'maxlength' => 8,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
        ->add('plano_interno', 'text',[
                'label' => 'Plano Interno',
                    'attr' => [
                        'id'=>'plano_interno',
                        'maxlength' => 11,
                        'oninput' => 'handleInput(event)'
                    ]
            ])
        ->add('valor', 'text',[
                'label' => 'Valor',
                'attr' => ['id'=>'valor']
            ])
        ->add('cancelar', 'button', [
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
                'id'=>'btn_inserir'
            ]
        ]);
    }

}
