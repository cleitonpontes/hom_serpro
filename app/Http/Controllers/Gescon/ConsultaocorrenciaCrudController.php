<?php

namespace App\Http\Controllers\Gescon;

// use App\Models\ContratoocorrenciaConsulta;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use Backpack\CRUD\CrudPanel;

/**
 * Class ConsultaocorrenciaCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 */
class ConsultaocorrenciaCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ContratoocorrenciaConsulta');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/ocorrencias');
        $this->crud->setEntityNameStrings('Ocorrência', 'Ocorrências');
        $this->crud->setHeading('Consulta Ocorrências por Contrato');
        $this->crud->enableExportButtons();

        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->removeAllButtons();

        $this->crud->addClause('join', 'contratos', 'contratos.id', '=', 'contratoocorrencias.contrato_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'users', 'users.id', '=', 'contratoocorrencias.user_id');
        $this->crud->addClause('join', 'codigoitens', 'codigoitens.id', '=', 'contratoocorrencias.situacao');
        $this->crud->addClause('join', 'codigoitens as codigoitensnova', 'codigoitensnova.id', '=', 'contratoocorrencias.novasituacao');
        $this->crud->addClause('select',
            [
                'contratoocorrencias.*',
                'contratos.id',
                'contratos.numero',
                'contratos.fornecedor_id',
                'contratos.objeto',
                'contratos.num_parcelas',
                'contratos.vigencia_inicio',
                'contratos.vigencia_fim',
                'contratos.valor_global',
                'contratos.valor_parcela',
                'fornecedores.cpf_cnpj_idgener',
                'fornecedores.nome',
                'users.cpf',
                'users.name',
                'unidades.codigosiasg',
                'codigoitens.id',
                'codigoitens.descricao'
            ]
        );

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'unidades.codigosiasg', '=', '110161');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns($this->retornaColunas());
    }

    public function retornaColunas()
    {
        $colunas = [
            [
                'name' => 'id',
                'label' => '#',
                'type' => 'number',
                'orderable' => false,
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => false,
                'visibleInShow' => false
            ],
            [
                'name' => 'contrato.unidade.codigosiasg',
                'label' => 'UG',
                'orderable' => false,
                'visibleInTable' => false,
                'visibleInModal' => false,
                'visibleInExport' => true,
                'visibleInShow' => true
            ],
            [
                'name' => 'contrato.numero',
                'label' => 'Número Contrato',
                'type' => 'string'
            ],
            [
                'name' => 'getFornecedor',
                'label' => 'Fornecedor',
                'type' => 'model_function',
                'function_name' => 'getFornecedor',
                'limit' => 100
            ],
            [
                'name' => 'contrato.objeto',
                'label' => 'Objeto',
                'limit' => 150
            ],
            [
                'name' => 'getVigenciaInicio',
                'label' => 'Vig. Início',
                'type' => 'model_function',
                'function_name' => 'getVigenciaInicio'
            ],
            [
                'name' => 'getVigenciaFim',
                'label' => 'Vig. Fim',
                'type' => 'model_function',
                'function_name' => 'getVigenciaFim'
            ],
            [
                'name' => 'getvalorGlobal',
                'label' => 'Valor Global',
                'type' => 'model_function',
                'function_name' => 'getvalorGlobal',
                'prefix' => 'R$ '
            ],
            [
                'name' => 'contrato.num_parcelas',
                'label' => 'Núm. Parcelas'
            ],
            [
                'name' => 'getValorParcela',
                'label' => 'Valor Parcela',
                'type' => 'model_function',
                'function_name' => 'getValorParcela',
                'prefix' => 'R$ '
            ],
            [
                'name' => 'getUsuario',
                'label' => 'Usuário',
                'type' => 'model_function',
                'function_name' => 'getUsuario'
            ],
            [
                'name' => 'data',
                'label' => 'Data',
                'type' => 'date',
                'format' => 'd/m/Y'
            ],
            [
                'name' => 'ocorrencia',
                'label' => 'Descrição',
                'limit' => 200
            ],
            [
                'name' => 'notificapreposto',
                'label' => 'Notifica Preposto',
                'type' => 'boolean',
                'options' => [0 => 'Não', 1 => 'Sim']
            ],
            [
                'name' => 'emailpreposto',
                'label' => 'E-mail Preposto',
                'type' => 'email',
                'limit' => 100
            ],
            [
                'name' => 'numeroocorrencia',
                'label' => 'Ocorrência Alterada',
                'type' => 'number'
            ],
            [
                'name' => 'getSituacao',
                'label' => 'Situação',
                'type' => 'model_function',
                'function_name' => 'getSituacao'
            ],
            [
                'name' => 'getSituacaoNova',
                'label' => 'Nova Situação',
                'type' => 'model_function',
                'function_name' => 'getSituacaoNova'
            ],
            [
                'name' => 'getArquivos',
                'label' => 'Arquivos',
                'type' => 'model_function',
                'function_name' => 'getArquivos'
            ]
        ];

        return $colunas;
    }
}
