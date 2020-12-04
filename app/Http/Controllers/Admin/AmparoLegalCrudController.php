<?php

namespace App\Http\Controllers\Admin;

use App\Models\Codigoitem;
use App\Models\AmparoLegal;
use App\Models\AmparoLegalRestricao;
use App\Models\Unidade;


use App\Http\Traits\Authorizes;
use App\Jobs\UserMailPasswordJob;
use App\Models\BackpackUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;



use Alert;
use App\Http\Controllers\AdminController;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Requests\AmparoLegalRequest as StoreRequest;
use App\Http\Requests\AmparoLegalRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\View\View;
use Exception;
use Redirect;
use Request;

/**
 * Class AmparoLegalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AmparoLegalCrudController extends CrudController
{
    /**
     * @throws Exception
     */
    public function setup(): void
    {
        if (backpack_user()->hasRole('Administrador')) {

            /*
            |--------------------------------------------------------------------------
            | CrudPanel Basic Information
            |--------------------------------------------------------------------------
            */
            $this->crud->setModel('App\Models\AmparoLegal');
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/admin/amparolegal');
            $this->crud->setEntityNameStrings('Amparo Legal', 'Amparos Legais');

            $this->crud->addClause('select', [
                // 'amparo_legal_restricoes.codigo_restricao as codigo_restricao',
                // Tabela principal deve ser sempre a última da listagem!
                'amparo_legal.id',
                'amparo_legal.modalidade_id',
                'amparo_legal.ato_normativo',
                'amparo_legal.artigo',
                'amparo_legal.paragrafo',
                'amparo_legal.inciso',
                'amparo_legal.alinea',
            ]);


            // $this->crud->addClause('leftJoin', 'amparo_legal_restricoes', 'amparo_legal_restricoes.amparo_legal_id', '=', 'amparo_legal.id');


            $this->crud->addClause('orderBy', 'id', 'DESC');

            // modalidades para o select
            $arrayModalidades = Codigoitem::whereHas('codigo', function ($query) {
                $query->where('descricao', '=', 'Modalidade Licitação');
            })
                ->orderBy('descricao')
                ->pluck('descricao', 'id')
                ->toArray();




            $idAmparoLegal = \Route::current()->parameter('amparolegal');
            $codigoRestricao = null;
            if(isset($idAmparoLegal)){
                $codigoRestricao = AmparoLegalRestricao::where('amparo_legal_id', $idAmparoLegal)->get()->first()->codigo_restricao;
            }





            $this->crud->enableExportButtons();
            $this->crud->addColumns($this->colunas());
            $this->crud->addFields($this->campos($arrayModalidades, $codigoRestricao));

            // $this->crud->denyAccess('create');
            // $this->crud->denyAccess('update');
            // $this->crud->denyAccess('delete');
            $this->crud->allowAccess('show');




            /*
            |--------------------------------------------------------------------------
            | CrudPanel Configuration
            |--------------------------------------------------------------------------
            */

            // add asterisk for fields that are required in IndicadorRequest
            $this->crud->setRequiredFields(StoreRequest::class, 'create');
            $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        } else {
            abort('403', config('app.erro_permissao'));
        }
    }

    private function campos($arrayModalidades, $codigoRestricao): array
    {
        return [
            [
                'label' => "Restricoes",
                'type' => 'select2_multiple',
                'name' => 'codigoitens',
                'entity' => 'codigoitens',
                'attribute' => 'descres',
                'attribute2' => 'descricao',
                'attribute_separator' => ' - ',
                'model' => Codigoitem::class,
                'pivot' => true,
                'options' => (function ($query) {
                    return $query
                        ->select(['codigoitens.descres as descres', 'codigoitens.descricao as descricao', 'codigoitens.id', 'codigoitens.codigo_id'])
                        ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
                        ->where('codigos.descricao', 'Modalidade Licitação')
                        ->orderBy('descricao', 'ASC')
                        ->get();
                }),
            ],
            [
                'name' => 'codigo_restricao',
                'label' => 'Código da Restrição',
                'type' => 'number',
                'default' => $codigoRestricao,
                // 'options' => (function ($query) {
                //     return $query::where('amparo_legal.id', $this->id)
                //         // ->select(['amparo_legal_restricoes.codigo_restricao'])
                //         ->join('amparo_legal_restricoes', 'amparo_legal_restricoes.amparo_legal_id', '=', 'amparo_legal.id')
                //         ->get()->first()['codigo_restricao'];
                // }),
            ],
            [
                'name' => 'codigo',
                'label' => 'Código',
                'type' => 'number',
            ],
            [ // select_from_array
                'name' => 'modalidade_id',
                'label' => "Modalidade",
                'type' => 'select2_from_array',
                'options' => $arrayModalidades,
                'allows_null' => false,
                'allows_multiple' => false, // OPTIONAL; needs you to cast this to array in your model;
            ],
            [
                'name' => 'ato_normativo',
                'label' => 'Ato Normativo',
                'type' => 'text',
            ],
            [
                'name' => 'artigo',
                'label' => 'Artigo',
                'type' => 'text',
            ],
            [
                'name' => 'paragrafo',
                'label' => 'Parágrafo',
                'type' => 'text',
            ],
            [
                'name' => 'inciso',
                'label' => 'Inciso',
                'type' => 'text',
            ],
            [
                'name' => 'alinea',
                'label' => 'Alínea',
                'type' => 'text',
            ],

        ];
    }
    // public function edit($id){
    //     return $objAmparoLegal = AmparoLegal::find($id)->();
    //     dd($id);
    // }
    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        $idAmparoLegalSalvo = $this->data['entry']->id;
        // vamos pegar todos os amparo_legal_restricoes pelo id do amparo legal e para cada um, salvar o código
        $arrayRestricoes = AmparoLegalRestricao::where('amparo_legal_id', $idAmparoLegalSalvo)->get();
        foreach($arrayRestricoes as $objRestricao){
            $objRestricao->codigo_restricao = $request->input('codigo_restricao');
            $objRestricao->save();
        }
        // // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // // your additional operations after save here
        $idAmparoLegalSalvo = $this->data['entry']->id;
        // vamos pegar todos os amparo_legal_restricoes pelo id do amparo legal e para cada um, salvar o código
        $arrayRestricoes = AmparoLegalRestricao::where('amparo_legal_id', $idAmparoLegalSalvo)->get();
        foreach($arrayRestricoes as $objRestricao){
            $objRestricao->codigo_restricao = $request->input('codigo_restricao');
            $objRestricao->save();
        }
        // // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /**
     * retorna as colunas para visualização (show)
     *
     * @return array
     * @author Saulo Soares <saulosao@gmail.com>
     */
    private function colunas(): array
    {
        return [

            [
                'name' => 'getModalidade',
                'label' => 'Modalidade', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getModalidade', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],
           [
                'name' => 'ato_normativo',
                'label' => 'Ato Normativo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'artigo',
                'label' => 'Artigo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'paragrafo',
                'label' => 'Parágrafo',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'inciso',
                'label' => 'Inciso',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
           [
                'name' => 'alinea',
                'label' => 'Alínea',
                'type' => 'text',
                'orderable' => true,
                'visibleInTable' => true,
                'visibleInModal' => true,
                'visibleInExport' => true,
                'visibleInShow' => true,
                // 'searchLogic' => function (Builder $query, $column, $searchTerm) {
                //     $query->orWhere('indicadores.nome', 'ilike', "%" . $searchTerm . "%");
                // },
            ],
            [
                'name' => 'getCodigoRestricao',
                'label' => 'Código da Restrição', // Table column heading
                'type' => 'model_function',
                'function_name' => 'getCodigoRestricao', // the method in your Model
                'orderable' => true,
                'visibleInTable' => true, // no point, since it's a large text
                'visibleInModal' => true, // would make the modal too big
                'visibleInExport' => true, // not important enough
                'visibleInShow' => true, // sure, why not
            ],

        ];
    }


    public function show($id): View
    {
        $content = parent::show($id);


        // return Siasgcompra::select(
        //     DB::raw("CONCAT(unidades.codigosiasg,' - ',unidades.nomeresumido) AS unidadecompra"),
        //     DB::raw("CONCAT(siasgcompras.numero,' - ',siasgcompras.ano) AS numerocompra"),
        //     'siasgcompras.id AS id'
        // )
        //     ->join('unidades', 'siasgcompras.unidade_id', '=', 'unidades.id')
        //     ->where('siasgcompras.id',$id)
        //     ->first();




        $this->crud->addColumn([
            'name' => 'getRestricoes',
            'label' => 'Restrições', // Table column heading
            'type' => 'model_function',
            'function_name' => 'getRestricoes', // the method in your Model
        ]);





        // $this->crud->addColumn([
        //     'name' => 'finalidade',
        //     'label' => 'Finalidade',
        //     'type' => 'text',
        //     'orderable' => true,
        //     'visibleInTable' => true,
        //     'visibleInModal' => true,
        //     'visibleInExport' => true,
        //     'visibleInShow' => true,
        //     'limit' => 10000
        // ]);

        return $content;
    }



}
