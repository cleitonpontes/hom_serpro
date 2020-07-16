<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SfcertificadoRequest as StoreRequest;
use App\Http\Requests\SfcertificadoRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class SfcertificadoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SfcertificadoCrudController extends CrudController
{
    public function setup()
    {

        if(!backpack_user()->hasRole('Administrador')){
            abort('403', config('app.erro_permissao'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SfCertificado');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/sfcertificado');
        $this->crud->setEntityNameStrings('Certificado Siafi', 'Certificado Siafi');


//        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('sfcertificado_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('sfcertificado_editar')) ? $this->crud->allowAccess('update') : null;
        (backpack_user()->can('sfcertificado_deletar')) ? $this->crud->allowAccess('delete') : null;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $colunas = $this->Colunas();
        $this->crud->addColumns($colunas);

        $campos = $this->Campos();
        $this->crud->addFields($campos);

        // add asterisk for fields that are required in SfcertificadoRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function Colunas()
    {
        $colunas = [
            [
                'name' => 'certificado',
                'label' => 'Certificado *.pem',
                'type' => 'upload_multiple',
                'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            ],
            [
                'name' => 'chaveprivada',
                'label' => 'Chave Privada *.key',
                'type' => 'upload_multiple',
                'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            ],
            [
                'name' => 'vencimento',
                'label' => 'Vencimento', // Table column heading
                'type' => 'date',
            ],
            [
                'name' => 'situacao',
                'label' => 'Situação',
                'type' => 'boolean',
                'options' => [0 => 'Inativo', 1 => 'Ativo']
            ],

        ];

        return $colunas;
    }
    public function Campos()
    {

        $campos = [
            [   // Upload
                'name' => 'certificado',
                'label' => 'Certificado *.pem',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            ],
            [   // Upload
                'name' => 'chaveprivada',
                'label' => 'Chave Privada *.key',
                'type' => 'upload_multiple',
                'upload' => true,
                'disk' => 'local' // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            ],
            [   // Date
                'name' => 'vencimento',
                'label' => 'Vencimento',
                'type' => 'date'
            ],
            [   // Date
                'name' => 'senhacertificado',
                'label' => 'Senha Certificado',
                'type' => 'password'
            ],
            [ // select_from_array
                'name' => 'situacao',
                'label' => "Situação",
                'type' => 'select_from_array',
                'options' => [1 => 'Ativo', 0 => 'Inativo'],
                'allows_null' => false,
            ],

        ];

        return $campos;
    }

    public function store(StoreRequest $request)
    {

        $senha = $request->input('senhacertificado');
        $request->request->set('senhacertificado', base64_encode($senha));

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $senha = $request->input('senhacertificado');
        $request->request->set('senhacertificado', base64_encode($senha));
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
