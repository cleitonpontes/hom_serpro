<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Catmatsergrupo;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contratopreposto;
use Backpack\CRUD\app\Http\Controllers\Operations\Response;
use Backpack\CRUD\CrudPanel;
use Exception;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ConsultaitemCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */
class ConsultaitemCrudController extends ConsultaContratoBaseCrudController
{
    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem()
    {
        $this->adicionaColunaTipoItem();
        $this->adicionaColunaNumeroItemCompra();
        $this->adicionaColunaItemGrupo();
        $this->adicionaColunaItem();
        $this->adicionaColunaQuantidade();
        $this->adicionaColunaValorUnitario();
        $this->adicionaColunaValorTotal();

    }

    /**
     *Adiciona o campo Tipo na listagem
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function adicionaColunaTipoItem()
    {
        $this->crud->addColumn([
            'name' => 'tipoitem',
            'label' => 'Tipo Item',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('contratoitens.tipo_id', 'ilike', "%$searchTerm%");
                $query->orWhere('codigoitens.descricao', 'ilike', "%$searchTerm%");
            },
        ]);
    }

    private function adicionaColunaItemGrupo()
    {
        $this->crud->addColumn([
            'name' => 'getCatmatsergrupo',
            'label' => 'Item Grupo',
            'type' => 'model_function',
            'function_name' => 'getCatmatsergrupo',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaItem()
    {
        $this->crud->addColumn([
            'name' => 'getCatmatseritem',
            'label' => 'Item',
            'type' => 'model_function',
            'function_name' => 'getCatmatseritem',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaNumeroItemCompra()
    {
        $this->crud->addColumn([
            'name' => 'numero_item_compra',
            'label' => 'Núm. item Compra',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true, // no point, since it's a large text
            'visibleInModal' => true, // would make the modal too big
            'visibleInExport' => true, // not important enough
            'visibleInShow' => true, // sure, why not
        ]);
    }

    private function adicionaColunaQuantidade()
    {
        $this->crud->addColumn([
            'name' => 'quantidade',
            'label' => 'Quantidade',
            'type' => 'number',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaValorUnitario()
    {
        $this->crud->addColumn([
            'name' => 'formatValorUnitarioItem',
            'label' => 'Valor Unitário',
            'type' => 'model_function',
            'function_name' => 'formatValorUnitarioItem',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaValorTotal()
    {
        $this->crud->addColumn([
            'name' => 'formatValorTotalItem',
            'label' => 'Valor Total',
            'type' => 'model_function',
            'function_name' => 'formatValorTotalItem',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
        $this->aplicaFiltroTipoItem();
        $this->aplicaFiltroItemGrupo();
    }

    /**
     * Adiciona o filtro ao campo Tipo
     *
     * @author Saulo Soares <saulosaso@gmail.com>
     */
    private function aplicaFiltroTipoItem()
    {
        $campo = [
            'name' => 'tipo',
            'type' => 'select2_multiple',
            'label' => 'Tipo Item'
        ];

        $dados = $this->retornaTiposParaCombo();

        $this->crud->addFilter(
            $campo,
            $dados,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratoitens.tipo_id', json_decode($value));
            }
        );
    }

    /**
     * Retorna array de Tipos para combo de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaTiposParaCombo()
    {
        $dados = Codigoitem::select('descricao', 'id');

        $dados->where('codigo_id', Codigo::TIPO_MATERIAL_SERVICO);
        $dados->orderBy('descricao');

        return $dados->pluck('descricao', 'id')->toArray();

    }

    /**
     * Adiciona o filtro ao campo Tipo
     *
     * @author Saulo Soares <saulosaso@gmail.com>
     */
    private function aplicaFiltroItemGrupo()
    {
        $campo = [
            'name' => 'item_grupo',
            'type' => 'select2_multiple',
            'label' => 'Item Grupo'
        ];

        $dados = $this->retornaItensGruposParaCombo();

        $this->crud->addFilter(
            $campo,
            $dados,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratoitens.grupo_id', json_decode($value));
            }
        );
    }

    /**
     * Retorna array de  para combo de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaItensGruposParaCombo()
    {
        $dados = Catmatsergrupo::join('contratoitens', 'contratoitens.grupo_id', '=', 'catmatsergrupos.id');
        $dados->join('contratos', 'contratos.id', '=', 'contratoitens.contrato_id');
        $dados->where('contratos.unidade_id', session()->get('user_ug_id'));
        $dados->where('contratos.situacao', true);

        $dados->orderBy('descricao');

        return $dados->pluck('catmatsergrupos.descricao', 'catmatsergrupos.id')->toArray();

    }

    /**
     * Configurações iniciais do Backpack
     *
     * @throws Exception
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\Contratoitem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/itens');
        $this->crud->setEntityNameStrings('Item', 'Itens');
        $this->crud->setHeading('Consulta Itens por Contrato');

        $this->crud->addClause('leftJoin', 'contratos',
            'contratos.id', '=', 'contratoitens.contrato_id'
        );
        $this->crud->addClause('leftJoin', 'fornecedores',
            'fornecedores.id', '=', 'contratos.fornecedor_id'
        );
        $this->crud->addClause('join', 'codigoitens',
            'codigoitens.id', '=', 'contratoitens.tipo_id'
        );
        $this->crud->addClause('select', [
            'contratos.*',
            'fornecedores.*',
            'codigoitens.descricao AS tipoitem',
            // Tabela principal deve ser sempre a última da listagem!
            'contratoitens.*',
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
     * @return Response
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumns([
            'contrato_id',
            'user_id',
            'tipo_id',
            'catmatseritem_id',
            'catmatsergrupos_id',
            'valortotal',
            'valorunitario',
            'grupo_id'

        ]);

        return $content;
    }

}
