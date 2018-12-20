<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class MudarUgForm extends Form
{
    public function buildForm()
    {
        $ugs = $this->getData('ugs') ?? "NULL";

        $this
            ->add('ug', 'select', [
                'label' => 'Unidade Gestora',
//                'rules' => "required",
                'attr' => [
//                    'class' => 'select2'
                ],
                'choices' => $ugs,
                'empty_value' => 'Selecione',
            ]);
    }
}
