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
                'required ' => true,
                'selected' => 'en',
                'empty_value' => 'Selecione...',
                'attr' => [
                    'id' => 'item'
                ]
            ])
            ->add('qtd_item', 'number',[
                'label' => 'Quantidade',
                    'attr' => [
                        'id'=>'qtd_item',
                        'maxlength' => 10,
                    ]
                ])
            ->add('vl_unit', 'number',[
                'label' => 'Valor Unitário',
                    'attr' => [
                        'id'=>'vl_unit',
                        ]
                ])
            ->add('vl_total', 'number',[
                'label' => 'Valor Total',
                'attr' => [
                    'id'=>'vl_total',
                ]
            ])
            ->add('periodicidade', 'number',[
                'label' => 'Periodicidade',
                'attr' => [
                    'id'=>'periodicidade',
                    'maxlength' => 10,
                ]
            ])
            ->add('data_inicio', 'date',[
                'label' => 'Data Início',
                'attr' => [
                    'id'=>'data_inicio',
                ]
            ])
            ->add('cancelar', 'submit', [
                'label' => '<i class="fa fa-reply"></i> Cancelar',
                'attr' => [
                    'class' => 'btn btn-danger',
                    'data-dismiss' => 'modal'
                ]
            ])
            ->add('incluir', 'button', [
                'label' => '<i class="fa fa-save"></i> Incluir',
                'attr' => [
                    'class' => 'btn btn-success',
                    'data-dismiss' => 'modal',
                    'id'=>'btn_inserir_item'
                ]
            ]);
    }
}
