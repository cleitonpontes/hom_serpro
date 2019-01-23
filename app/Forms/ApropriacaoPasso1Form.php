<?php
namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class ApropriacaoPasso1Form extends Form
{

    // protected $name = 'importacao';
    public function buildForm()
    {
        $this->add('arquivos', 'file', [
            'label' => 'Selecionar até 3 arquivos DDP (.txt) a importar',
            'rules' => 'required',
            'attr' => [
                'multiple' => 'multiple',
                'autofocus' => 'autofocus'
            ]
        ])
        ->add('cancelar', 'button', [
            'label' => '<i class="fa fa-reply"></i> Cancelar',
            'attr' => [
                'class' => 'btn btn-danger',
                'onclick' => 'window.location="/folha/apropriacao";'
            ]
        ])
        ->add('importar', 'submit', [
            'label' => '<i class="fa fa-save"></i> Importar',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);
    }
}