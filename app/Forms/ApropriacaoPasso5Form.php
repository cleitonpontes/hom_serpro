<?php
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ApropriacaoPasso5Form extends Form
{

    // protected $name = 'importacao';
    public function buildForm()
    {
        $this->add('ateste', 'date', [
            'label' => 'Data do ateste',
            'rules' => 'required',
            'attr' => [
                'autofocus' => 'autofocus'
            ]
        ])
        ->add('nup', 'text', [
            'label' => 'N.U.P. (Número único de protocolo)',
            'rules' => 'required|max:20'
        ])
        ->add('doc_origem', 'text', [
            'label' => 'Número do documento de origem',
            'rules' => 'required|max:17'
        ])
        ->add('centro_custo', 'text', [
            'label' => 'Cód. Centro de Custo',
            'rules' => 'required|max:11',
            'attr' => [
                'onkeyup' => "maiuscula(this)"
            ]
        ])
        ->add('observacoes', 'textarea', [
            'label' => 'Observações',
            'rules' => 'required|max:468',
        ])
        ->add('importar', 'submit', [
            'label' => '<i class="fa fa-save"></i> Salvar',
            'attr' => [
                'class' => 'btn btn-success text-right'
            ]
        ])
        ->add('id', 'hidden');
    }
}
