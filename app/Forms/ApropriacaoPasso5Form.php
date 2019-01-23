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
            'rules' => 'required|max:50'
        ])
        ->add('doc_origem', 'text', [
            'label' => 'Número do documento de origem',
            'rules' => 'required|max:50'
        ])
        ->add('observacoes', 'textarea', [
            'label' => 'Observações',
            'rules' => 'required',
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