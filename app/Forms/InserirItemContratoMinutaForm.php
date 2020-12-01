<?php
namespace App\Forms;

use App\Models\Codigoitem;
use Kris\LaravelFormBuilder\Form;

class InserirItemContratoMinutaForm extends Form
{

    public function buildForm()
    {

        $tipos = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo CATMAT e CATSER');
        })
            ->pluck('descricao', 'id')
            ->toArray();

        $this
            ->add('tipo_item', 'select', [
                'choices' => $tipos,
                'required ' => true,
                'empty_value' => 'Selecione...',
                'attr' => [
                    'id' => 'tipo_item',
                    'onchange' => 'return carregaitensmodal(this)'
                ]
            ])
            ->add('item', 'select', [
                'choices' => [
//                    '149' => 'MATERIAL',
//                    '150' => 'SERVIÇO',
                ],
                'required ' => true,
                'selected' => 'en',
                'empty_value' => 'Selecione...',
                'attr' => [
                    'id' => 'item'
                ]
            ])
        ->add('quantidade', 'number',[
            'label' => 'Quantidade',
                'attr' => [
                    'id'=>'quantidade',
                    'maxlength' => 10,
                    'onkeypress' => 'return somenteNumeros(event)'
                ]
            ])
        ->add('valor_unitario', 'text',[
            'label' => 'Valor Unitário',
                'attr' => [
                    'id'=>'valor_unitario',
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
