<?php

namespace App\Http\Controllers\Gescon;

use App\Models\BackpackUser;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Contratoresponsavel;
use App\Models\Instalacao;
use Backpack\CRUD\CrudPanel;
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
            'contratopreposto.*'
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
            'funcao_id',
            'instalacao_id',
            'data_inicio',
            'data_fim'
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
        $this->adicionaColunaNome();

    }

    private function adicionaColunaNome()
    {
        $this->crud->addColumn([
            'name' => 'nome',
            'label' => 'Nome',
            'type' => 'text',
        ]);
    }

    /**
     * Adiciona filtros específicos a serem apresentados
     *
     * @author Saulo Soares <saulosao@gmail.com>
     */
    public function aplicaFiltrosEspecificos()
    {
    }


}
