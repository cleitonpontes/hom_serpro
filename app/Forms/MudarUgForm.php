<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class MudarUgForm extends Form
{
    public function buildForm()
    {



        if (backpack_user()->hasRole('Administrador')) {
            $ugs = $this->getData('ugs') ?? "NULL";
            $this
                ->add('ug', 'select', [
                    'label' => 'UG/UASG',
    //                'rules' => "required",
                    'attr' => [
                       'class' => 'select2_from_ajax'
                    ],
                    'choices' => $ugs,
                    'empty_value' => 'Selecione',
                ]);

        } else {
            $ugs = $this->getData('ugs') ?? "NULL";
            $this
                ->add('ug', 'select', [
                    'label' => 'UG/UASG',
    //                'rules' => "required",
                    'attr' => [
    //                    'class' => 'select2'
                    ],
                    'choices' => $ugs,
                    'empty_value' => 'Selecione',
                ]);
        }



    }

}
