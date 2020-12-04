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
                'ci_modalidade.id as id_ci_modalidade',
                // 'ci_modalidade.descricao',
                // 'ci_modalidade.descres',
                // Tabela principal deve ser sempre a última da listagem!
                'amparo_legal.id',
                'amparo_legal.modalidade_id',
                'amparo_legal.ato_normativo',
                'amparo_legal.artigo',
                'amparo_legal.paragrafo',
                'amparo_legal.inciso',
                'amparo_legal.alinea',
            ]);
            $this->crud->addClause('join', 'codigoitens as ci_modalidade', 'ci_modalidade.id', '=', 'amparo_legal.modalidade_id');
            // $this->crud->addClause('leftJoin', 'amparo_legal_restricoes', 'amparo_legal_restricoes.amparo_legal_id', '=', 'amparo_legal.id');
            // $this->crud->addClause('join', 'codigoitens as ci_amparo_legal', 'ci_amparo_legal.id', '=', 'amparo_legal_restricoes.tipo_restricao_id');
            $this->crud->addClause('orderBy', 'id', 'DESC');

            // modalidades para o select
            $arrayModalidades = Codigoitem::whereHas('codigo', function ($query) {
                $query->where('descricao', '=', 'Modalidade Licitação');
            })
                ->orderBy('descricao')
                ->pluck('descricao', 'id')
                ->toArray();

            $this->crud->enableExportButtons();
            $this->crud->addColumns($this->colunas());
            $this->crud->addFields($this->campos($arrayModalidades));

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

    private function campos($arrayModalidades): array
    {


        // dd($arrayModalidades);

        return [

            // [ // select_from_array
            //     'name' => 'amparo_legal_restricoes',
            //     'label' => "Restricoes",
            //     'type' => 'select2_from_array',
            //     'options' => $arrayModalidades,
            //     'allows_null' => false,
            //     'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            // ],

            [
                'label' => "Restricoes",
                'type' => 'select2_multiple',
                'name' => 'amparo_legal_restricoes',
                'entity' => 'restricoes',
                'attribute' => 'descricao',
                // 'model' => "App\Models\AmparoLegalRestricao",
                'model' => "App\Models\Codigoitem",
                'pivot' => true,

                'options' => (function ($query) {
                    return $query
                        ->select(['codigoitens.descres as descres', 'codigoitens.descricao as descricao', 'codigoitens.id', 'codigoitens.codigo_id'])
                        ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
                        ->where('codigos.descricao', 'Modalidade Licitação')



                        // ->orderBy('numero', 'ASC')
                        // ->select(['id', DB::raw('case
                        //    when left(numero, 4) = date_part(\'year\', current_date)::text
                        //        then numero || \' - Saldo a Liquidar: R$ \' || aliquidar
                        //    else numero || \' - Saldo RP  a Liquidar: R$ \' || rpaliquidar
                        //    end as numero')
                        // ])
                        // ->where('unidade_id', session()->get('user_ug_id'))
                        // ->where('fornecedor_id', $con->fornecedor_id)
                        ->get();
                }),

            ],


            // [
            //     // n-n relationship
            //     'label' => "Restricoes2", // Table column heading
            //     'type' => "select2_from_ajax_multiple",
            //     'name' => 'amparo_legal_restricoes', // the column that contains the ID of that connected entity
            //     'entity' => 'restricoes', // the method that defines the relationship in your Model
            //     'attribute' => "descres", // foreign key attribute that is shown to user
            //     'attribute2' => "descricao", // foreign key attribute that is shown to user
            //     'process_results_template' => 'gescon.process_results_multiple_tipo_restricao',
            //     'model' => "App\Models\AmparoLegalRestricao", // foreign key model
            //     'data_source' => url("api/codigoitemAmparoLegal"), // url to controller search function (with /{id} should return model)
            //     'placeholder' => "Selecione a(s) restrição(ões)", // placeholder for the select
            //     'minimum_input_length' => 2, // minimum characters to type before querying results
            //     'pivot' => false, // on create&update, do you need to add/delete pivot table entries?
            // ],

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
    public function store(StoreRequest $request)
    {

        // início forma manual

        $objAmparoLegal = new AmparoLegal();
        $objAmparoLegal->codigo = $request->input('codigo');
        $objAmparoLegal->modalidade_id = $request->input('modalidade_id');
        $objAmparoLegal->ato_normativo = $request->input('ato_normativo');
        $objAmparoLegal->artigo = $request->input('artigo');
        $objAmparoLegal->paragrafo = $request->input('paragrafo');
        $objAmparoLegal->inciso = $request->input('inciso');
        $objAmparoLegal->alinea = $request->input('alinea');
        if( !$objAmparoLegal->save() ){
            \Alert::error('Erro ao salvar os dados do Amparo Legal!')->flash();
            return redirect()->back();
        }
        // aqui já salvou o amparo legal - vamos salvar as restrições
        $idAmparoLegal = $objAmparoLegal->id;
        // aqui receberemos um array com os codigoitens, para salvarmos na tabela amparo legal restricao
        $arrayCodigoitens = $request->input('amparo_legal_restricoes');
        foreach( $arrayCodigoitens as $idCodigoItemSalvarRestricao ){
            $objAmparoLegalRestricao = new AmparoLegalRestricao();
            $objAmparoLegalRestricao->amparo_legal_id = $idAmparoLegal;
            $objAmparoLegalRestricao->tipo_restricao_id = $idCodigoItemSalvarRestricao;
            $objAmparoLegalRestricao->codigo_restricao = $request->input('codigo');
            if( !$objAmparoLegalRestricao->save() ){
                AmparoLegal::find($idAmparoLegal)->delete();
                \Alert::error('Erro ao salvar os dados das Restrições!')->flash();
                return redirect()->back();
            }
        }
        \Alert::success('Registro salvo com sucesso!')->flash();
        $redirectUrl = Request::has('http_referrer') ? Request::get('http_referrer') : $this->crud->route;
        return Redirect::to($redirectUrl);

        // fim forma manual



        // // return redirect('/admin/amparolegal');
        // $redirect_location = parent::storeCrud($request);
        // // your additional operations after save here
        // // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;


    }

    public function update(UpdateRequest $request)
    {

        // início forma manual
        $idAmparoLegal = $request->input('id');
        $objAmparoLegal = AmparoLegal::where('id', $idAmparoLegal)->first();
        $objAmparoLegal->codigo = $request->input('codigo');
        $objAmparoLegal->modalidade_id = $request->input('modalidade_id');
        $objAmparoLegal->ato_normativo = $request->input('ato_normativo');
        $objAmparoLegal->artigo = $request->input('artigo');
        $objAmparoLegal->paragrafo = $request->input('paragrafo');
        $objAmparoLegal->inciso = $request->input('inciso');
        $objAmparoLegal->alinea = $request->input('alinea');
        if( !$objAmparoLegal->save() ){
            \Alert::error('Erro ao salvar os dados do Amparo Legal!')->flash();
            return redirect()->back();
        }
        // aqui já salvou o amparo legal - vamos excluir as restrições existentes e salvar as novas
        if( !$arrayRestricoes = AmparoLegalRestricao::where('amparo_legal_id', $idAmparoLegal)->delete() ){
            \Alert::error('Erro ao excluir as Restrições antigas!')->flash();
                return redirect()->back();
        }

        // aqui receberemos um array com os codigoitens, para salvarmos na tabela amparo legal restricao
        $arrayCodigoitens = $request->input('amparo_legal_restricoes');
        foreach( $arrayCodigoitens as $idCodigoItemSalvarRestricao ){
            $objAmparoLegalRestricao = new AmparoLegalRestricao();
            $objAmparoLegalRestricao->amparo_legal_id = $idAmparoLegal;
            $objAmparoLegalRestricao->tipo_restricao_id = $idCodigoItemSalvarRestricao;
            $objAmparoLegalRestricao->codigo_restricao = $request->input('codigo');
            if( !$objAmparoLegalRestricao->save() ){
                AmparoLegal::find($idAmparoLegal)->delete();
                \Alert::error('Erro ao salvar os dados das Restrições!')->flash();
                return redirect()->back();
            }
        }
        \Alert::success('Registro salvo com sucesso!')->flash();
        $redirectUrl = Request::has('http_referrer') ? Request::get('http_referrer') : $this->crud->route;
        return Redirect::to($redirectUrl);

        // fim forma manual





        // // your additional operations before save here
        // $redirect_location = parent::updateCrud($request);
        // // your additional operations after save here
        // // use $this->data['entry'] or $this->crud->entry
        // return $redirect_location;
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
