<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contratopreposto;
use App\Models\Contratoresponsavel;
use App\Models\Instalacao;
use Backpack\CRUD\CrudPanel;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ConsultaresponsavelCrudController
 * @package App\Http\Controllers\Gescon
 * @property-read CrudPanel $crud
 * @author Saulo Soares <saulosao@gmail.com>
 */
class ConsultaprepostoCrudController extends ConsultaContratoBaseCrudController
{
    /**
     * Adiciona as colunas específicas a serem exibidas bem como suas definições
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function adicionaColunasEspecificasNaListagem()
    {
        $this->adicionaColunaNome();
        $this->adicionaColunaEmail();
        $this->adicionaColunaTelefone();
        $this->adicionaColunaCelular();
        $this->adicionaColunaDocumento();
        $this->adicionaColunaInfComplementar();
        $this->adicionaColunaDataInicio();
        $this->adicionaColunaDataFim();
        $this->adicionaColunaSituacao();

    }

    private function adicionaColunaNome()
    {
        $this->crud->addColumn([
            'name' => 'nome',
            'label' => 'Preposto',
            'type' => 'text',
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('contratopreposto.nome', 'ilike', "%$searchTerm%");
            },

        ]);
    }

    private function adicionaColunaEmail()
    {
        $this->crud->addColumn([
            'name' => 'email',
            'label' => 'E-mail',
            'type' => 'email',
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaTelefone()
    {
        $this->crud->addColumn([
            'name' => 'telefonefixo',
            'label' => 'Telefone Fixo',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaCelular()
    {
        $this->crud->addColumn([
            'name' => 'celular',
            'label' => 'Celular',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaDocumento()
    {
        $this->crud->addColumn([
            'name' => 'doc_formalizacao',
            'label' => 'Doc. Formalização',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaInfComplementar()
    {
        $this->crud->addColumn([
            'name' => 'informacao_complementar',
            'label' => 'Inform. Complementar',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaDataInicio()
    {
        $this->crud->addColumn([
            'name' => 'data_inicio',
            'label' => 'Data Início',
            'type' => 'date',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaDataFim()
    {
        $this->crud->addColumn([
            'name' => 'data_fim',
            'label' => 'Data Fim',
            'type' => 'date',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    private function adicionaColunaSituacao()
    {
        $this->crud->addColumn([
            'name' => 'situacao',
            'label' => 'Situação',
            'type' => 'boolean',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            // optionally override the Yes/No texts
            'options' => [0 => 'Inativo', 1 => 'Ativo']
        ]);
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
        $this->aplicaFiltroPreposto();
        $this->aplicaFiltroDataInicio();
    }

    /**
     * Adiciona o filtro ao campo Preposto
     *
     * @author Saulo Soares <saulosaso@gmail.com>
     */
    private function aplicaFiltroPreposto()
    {
        $campo = [
            'name' => 'preposto',
            'type' => 'select2_multiple',
            'label' => 'Preposto'
        ];

        $dados = $this->retornaPrepostoParaCombo();

        $this->crud->addFilter(
            $campo,
            $dados,
            function ($value) {
                $this->crud->addClause('whereIn'
                    , 'contratopreposto.nome', json_decode($value));
            }
        );
    }

    /**
     * Retorna array de  para combo de filtro
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function retornaPrepostoParaCombo()
    {
        $dados = Contratopreposto::select('nome', 'contratopreposto.id');
        $dados->join('contratos', 'contratos.id', '=', 'contratopreposto.contrato_id');

        $dados->where('contratos.unidade_id', session()->get('user_ug_id'));
        $dados->where('contratos.situacao', true);

        return $dados->pluck('nome', 'nome')->toArray();
    }

    /**
     * Adiciona o filtro ao campo DataInicio
     *
     * @author Saulo Soares <saulosaso@gmail.com>
     */
    private function aplicaFiltroDataInicio()
    {
        $campo = [
            'name' => 'data_inicio',
            'type' => 'date_range',
            'label' => 'Data Início'
        ];

        $this->crud->addFilter(
            $campo,
            null,
            function ($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'contratopreposto.data_inicio', '>=', $dates->from . ' 00:00:00');
                $this->crud->addClause('where', 'contratopreposto.data_inicio', '<=', $dates->to . ' 23:59:59');
            }
        );
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

        $this->crud->setModel('App\Models\Contratopreposto');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/consulta/prepostos');
        $this->crud->setEntityNameStrings('Preposto', 'Prepostos');
        $this->crud->setHeading('Consulta Prepostos por Contrato');

        $this->crud->addClause('leftJoin', 'contratos',
            'contratos.id', '=', 'contratopreposto.contrato_id'
        );
        $this->crud->addClause('leftJoin', 'fornecedores',
            'fornecedores.id', '=', 'contratos.fornecedor_id'
        );
        $this->crud->addClause('select', [
            'contratos.*',
            'fornecedores.*',
            // Tabela principal deve ser sempre a última da listagem!
            'contratopreposto.*',
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
            'user_id',
        ]);

        return $content;
    }
}
