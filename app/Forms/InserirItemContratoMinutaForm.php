<?php
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class InserirItemContratoMinutaForm extends Form
{

    public function buildForm()
    {

        $this
        ->add('tipo_item', 'text',[
            'label' => 'Tipo Item',
            'rules' => 'required',
                'attr' => [
                    'id'=>'tipo_item'
                ]
            ])
        ->add('item','text',[
            'label' => 'Item',
            'required ' => true,
                'attr' => [
                    'id'=>'item'
                ]
            ])
        ->add('quantidade', 'number',[
            'label' => 'Quantidade',
                'attr' => [
                    'id'=>'quantidade',
                    'maxlength' => 10,
                    'required ' => true,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
        ->add('valor_unitario', 'text',[
            'label' => 'Valor Unitário',
                'attr' => [
                    'id'=>'valor_unitario',
                    'required ' => true,
                    'onkeypress' => 'return somenteNumeros(event)'
                    ]
            ])
        ->add('valor_total', 'text',[
            'label' => 'UGR',
                'attr' => [
                    'id'=>'valor_total',
                    'required ' => true,
                    'onkeypress' => 'return somenteNumeros(event)'
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
                'id'=>'btn_inserir_item'
            ]
        ]);
    }

}
