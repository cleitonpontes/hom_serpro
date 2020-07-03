<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contratoresponsavel;
use App\Models\Contratoarquivo;
use App\Models\Instalacao;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\CrudPanel;

/**
 * Class ConsultaresponsavelCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */
class ConsultaarquivoCrudController extends ConsultaContratoBaseCrudController
{
    /**
     * Configurações iniciais do Backpack
     *
     * @throws \Exception
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratoarquivo');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/arquivos');
        $this->crud->setEntityNameStrings('Arquivo', 'Arquivos');
        $this->crud->setHeading('Consulta Arquivos por Contrato');

        $this->crud->addClause('leftJoin', 'contratos',
            'contratos.id', '=', 'contrato_arquivos.contrato_id'
        );
        $this->crud->addClause('leftJoin', 'fornecedores',
            'fornecedores.id', '=', 'contratos.fornecedor_id'
        );
        $this->crud->addClause('join', 'codigoitens',
            'codigoitens.id', '=', 'contrato_arquivos.tipo'
        );
        $this->crud->addClause('select', [
            'contratos.*',
            'fornecedores.*',
            'codigoitens.descricao as tipoArquivo',
            // Tabela principal deve ser sempre a última da listagem!
            'contrato_arquivos.*'
        ]);

        // Apenas ocorrências da unidade atual
        $this->crud->addClause('where', 'contratos.unidade_id', '=', session('user_ug_id'));

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->defineConfiguracaoPadrao();
    }

    /**
     * Action para exibição de um único registro
     *
     * @param int $id
     * @return \Backpack\CRUD\app\Http\Controllers\Operations\Response
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contrato_id',
            'tipo',
        ]);

        return $content;
    }

    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem()
    {
        $this->adicionaColunaTipo();
        $this->adicionaColunaProcesso();
        $this->adicionaColunaSei();
        $this->adicionaColunaDescricao();
        $this->adicionaColunaArquivo();
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
//        $this->aplicaFiltroTipo();
    }

    /**
     *Adiciona o campo Tipo na listagem
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function adicionaColunaTipo()
    {
        $this->crud->addColumn([
            'name' => 'tipoArquivo',
            'label' => 'Tipo',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
                'searchLogic' => function (Builder $query, $column, $searchTerm) {
                    $query->orWhere('contrato_arquivos.tipo', 'like', "%$searchTerm%");
                    $query->orWhere('codigoitens.descricao', 'like', "%$searchTerm%");
                },
        ]);
    }

    private function adicionaColunaArquivo()
    {
        $this->crud->addColumn([
            'name' => 'arquivos',
            'label' => 'Arquivos',
            'type' => 'arquivos_ico',
            'disk' => 'local',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaProcesso()
    {
        $this->crud->addColumn([
            'name' => 'contrato.processo',
            'label' => 'Processo',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('contratos.processo',
                    'ilike', '%' . $searchTerm . '%'
                );
            }

            //                'searchLogic' => function (Builder $query, $column, $searchTerm) {
//                    $query->orWhere('orgaossuperiores.codigo', 'like', "%$searchTerm%");
//                    $query->orWhere('orgaossuperiores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
//                },

        ]);
    }

    private function adicionaColunaSei()
    {
        $this->crud->addColumn([   // Number
            'name' => 'sequencial_documento',
            'label' => 'Nº SEI / Chave Acesso Sapiens',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    private function adicionaColunaDescricao()
    {
        $this->crud->addColumn([   // Number
            'name' => 'descricao',
            'label' => 'Descrição',
            'type' => 'text',
            'limit' => 1000,
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('contrato_arquivos.descricao',
                    'ilike', '%' . $searchTerm . '%'
                );
            }
        ]);
    }

}


