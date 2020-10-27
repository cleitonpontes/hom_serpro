<?php
namespace App\Forms;

use App\Models\Codigoitem;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\Form;

class InserirCelulaOrcamentariaForm extends Form
{

    // protected $name = 'importacao';
    public function buildForm()
    {

        $this
//        ->add('modalidade', 'select', [
//            'choices' => $this->getModalidades(),
//            'selected' => '',
//            'empty_value' => 'Selecione a modalidade...'
//        ])
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
//            ->add('uasg_compra', 'select', [
//                'choices' => $this->getUasgs(),
//                'selected' => '',
//                'attr' => ['multiple' => false],
//                'empty_value' => 'Selecione a Uasg da Compra...'
//            ])
//        ->add('cancelar', 'button', [
//            'label' => '<i class="fa fa-reply"></i> Cancelar',
//            'attr' => [
//                'class' => 'btn btn-danger',
//                'onclick' => 'window.location="/empenho/listaempenhos";'
//            ]
//        ])
        ->add('inserir', 'submit', [
            'label' => '<i class="fa fa-save"></i> Salvar',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);
    }

    public function getModalidades()
    {
        $modalidades = Codigoitem::where('codigo_id',13)
                                ->where('visivel',true)
                                ->pluck('descricao','descres')
                                ->toArray();
        return $modalidades;
    }

    public function getUasgs()
    {
        $uasgCompra = Unidade::select('unidades.codigo',
                        DB::raw("CONCAT(unidades.codigo,'-',unidades.nomeresumido) AS unidadecompra")
                    )
                    ->pluck('unidadecompra','codigo')
                    ->toArray();
        return $uasgCompra;
    }
}
