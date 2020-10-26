<?php
namespace App\Forms;

use App\Models\Codigoitem;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use Kris\LaravelFormBuilder\Form;

class NovoEmepenhoTela1Form extends Form
{

    // protected $name = 'importacao';
    public function buildForm()
    {

        $this->add('modalidade', 'select', [
            'choices' => $this->getModalidades(),
            'selected' => '',
            'empty_value' => 'Selecione a modalidade...'
        ])
        ->add('numero_ano', 'text', [
            'value' => ''
        ])
        ->add('uasg_usuario', 'text', [
                'value' => session('user_ug'),
                'attr' => ['disabled' => true]
        ])
            ->add('uasg_compra', 'select', [
                'choices' => $this->getUasgs(),
                'selected' => '',
                'attr' => ['multiple' => false],
                'empty_value' => 'Selecione a Uasg da Compra...'
            ])
        ->add('cancelar', 'button', [
            'label' => '<i class="fa fa-reply"></i> Cancelar',
            'attr' => [
                'class' => 'btn btn-danger',
                'onclick' => 'window.location="/empenho/listaempenhos";'
            ]
        ])
        ->add('importar', 'submit', [
            'label' => '<i class="fa fa-save"></i> Avançar',
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
