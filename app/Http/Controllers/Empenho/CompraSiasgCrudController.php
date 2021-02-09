<?php

namespace App\Http\Controllers\Empenho;

use Alert;

use App\Http\Traits\BuscaCodigoItens;
use App\Http\Traits\Formatador;
use App\Models\Catmatseritem;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\Contrato;
use App\Models\CompraItem;
use App\Models\CompraItemFornecedor;
use App\Models\CompraItemMinutaEmpenho;
use App\Models\CompraItemUnidade;
use App\Models\ContratoMinutaEmpenhoPivot;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompraSiasgRequest as StoreRequest;
use App\Http\Requests\CompraSiasgRequest as UpdateRequest;
use http\Params;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Route;
use App\Http\Traits\CompraTrait;

class CompraSiasgCrudController extends CrudController
{
    use Formatador;
    use CompraTrait;
    use BuscaCodigoItens;

    public const MATERIAL = [149, 194];
    public const SERVICO = [150, 195];
    public const SISPP = 1;
    public const SISRP = 2;

//    const TIPOCOMPRASIASG =

    public function setup()
    {

        $modalidades = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Modalidade Licitação');
        })
            ->whereRaw('LENGTH(descres) <= 2')
            ->orderBy('descres')
            ->select(DB::raw("CONCAT(descres,' - ',descricao) AS descres_descricao"), 'id')
            ->pluck('descres_descricao', 'id');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Compra');
        $this->crud->setRoute(
            config('backpack.base.route_prefix')
            . '/empenho/buscacompra'
        );
        $this->crud->setEntityNameStrings('Buscar Compra', 'Buscar Compras');
        $this->crud->setCreateView('vendor.backpack.crud.empenho.create');
        $this->crud->urlVoltar = route('empenho.crud./minuta.index');

//        $this->crud->denyAccess('create');
//        $this->crud->denyAccess('update');
//        $this->crud->denyAccess('delete');
//        $this->crud->denyAccess('show');

//        (backpack_user()->can('glosa_inserir')) ? $this->crud->allowAccess('create') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        //$this->columns();
        $this->fields($modalidades);

        // add asterisk for fields that are required in GlosaRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function fields(Collection $modalidades): void
    {
        $this->setFielContrato();
        $this->setFieldUnidadeCompra();
        $this->setFieldModalidade($modalidades);
        $this->setFieldNumeroAno();
        $this->setFieldFornecedor();
    }

    private function setFieldModalidade($modalidade): void
    {
        $this->crud->addField([
            'name' => 'modalidade_id',
            'label' => "Modalidade Licitação",
            'type' => 'select2_from_array',
            'options' => $modalidade,
            'allows_null' => true,
            'attributes' => [
                'disabled' => 'disabled',
                'class' => 'form-control select2_from_array opc_compra',
                'id' => 'opc_compra_modalidade'
            ],
        ]);
    }

    private function setFieldNumeroAno(): void
    {
        $this->crud->addField([
            'name' => 'numero_ano',
            'label' => 'Numero / Ano',
            'type' => 'numcontrato',
            'attributes' => [
                'disabled' => 'disabled',
                'class' => 'form-control opc_compra',
            ],
        ]);
    }

    private function setFieldUnidadeCompra(): void
    {
        $this->crud->addField([
            'label' => "Unidade Compra",
            'type' => "select2_from_ajax",
            'name' => 'unidade_origem_id',
            'entity' => 'unidade_origem',
            'attribute' => "codigo",
            'attributes' => [
                'disabled' => 'disabled',
                'class' => 'form-control opc_compra',
                'id' => 'opc_compra_unidade'
            ],
            'attribute2' => "nomeresumido",
            'process_results_template' => 'gescon.process_results_unidade',
            'model' => "App\Models\Unidade",
            'data_source' => url("api/unidade"),
            'placeholder' => "Selecione a Unidade",
            'minimum_input_length' => 2,

        ]);
    }

    private function setFielContrato(): void
    {
        $this->crud->addField([
            'label' => "Contrato",
            'type' => "select2_from_ajax",
            'name' => 'id',
            'entity' => 'contratos',
            'attribute' => "numero",
            'model' => "App\Models\Contrato",
            'data_source' => url("api/contrato/numero"),
            'placeholder' => "Selecione um Contrato",
            'minimum_input_length' => 2,
            'attributes' => [
                'class' => 'form-control opc_contrato',
                'id' => 'opc_contrato_numero'
            ],
        ]);
    }

    private function setFieldFornecedor()
    {
        $this->crud->addField([
            'label' => "Suprido",
            'type' => "select2_from_ajax_suprido",
            'name' => 'fornecedor_empenho_id',
            'entity' => 'fornecedor',
            'attribute' => "cpf_cnpj_idgener",
            'model' => Fornecedor::class,
            'data_source' => url("api/suprido"),
            'placeholder' => "Selecione o suprido",
            'minimum_input_length' => 2,
            'attributes' => [
                'disabled' => 'disabled',
                'class' => 'form-control opc_suprimento',
            ],
        ]);
    }

    public function show($id)
    {
        $content = parent::show($id);

//        $this->crud->removeColumn('contratoitem_servico_indicador_id');

        return $content;
    }

    public function store(StoreRequest $request)
    {
        //CONTRATO
        if ($request->tipoEmpenho == 1) {
            $contrato = Contrato::find($request->id);

            $params = [
                'modalidade' => $contrato->modalidade->descres,
                'numeroAno' => $contrato->licitacao_numero_limpa,
                'uasgCompra' => $contrato->unidadecompra->codigo,
                'uasgUsuario' => $contrato->unidadeorigem->codigo
            ];

            //pegar a compra
            $apiSiasg = new ApiSiasg();
            $retorno_compra = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

            if (is_null($retorno_compra->data)) {
                return redirect('/empenho/buscacompra')->with('alert-warning', $retorno_compra->messagem);
            }

            $unidade_autorizada = $this->verificaPermissaoUasgCompraParamContrato($contrato);

            if (is_null($unidade_autorizada)) {
                return redirect('/empenho/buscacompra')
                    ->with('alert-warning', 'Você não tem permissão para realizar empenho para esta unidade!');
            }

            $request->request->set('numero_ano', $contrato->numero);
            $request->request->set('unidade_origem_id', $contrato->unidadeorigem_id);
            $request->request->set('modalidade_id', $contrato->modalidade->id);

            $this->montaParametrosCompra($retorno_compra, $request);

            $situacao = Codigoitem::wherehas('codigo', function ($q) {
                $q->where('descricao', '=', 'Situações Minuta Empenho');
            })
                ->where('descricao', 'EM ANDAMENTO')
                ->first();

            DB::beginTransaction();

            try {
                $compra = $this->updateOrCreateCompra($request);

                if ($retorno_compra->data->compraSispp->tipoCompra == 1) {
                    $this->gravaParametroItensdaCompraSISPP($retorno_compra, $compra);
                }

                if ($retorno_compra->data->compraSispp->tipoCompra == 2) {
                    $this->gravaParametroItensdaCompraSISRP($retorno_compra, $compra);
                }

                $tipo_empenhopor = Codigoitem::where('descricao', '=', 'Contrato')
                    ->where('descres', 'CON')
                    ->first();

                $minutaEmpenho = $this->gravaMinutaEmpenho([
                    'situacao_id' => $situacao->id,
                    'compra_id' => $compra->id,
                    'contrato_id' => $contrato->id,
                    'unidade_origem_id' => $contrato->unidadeorigem_id,
                    'unidade_id' => $unidade_autorizada,
                    'modalidade_id' => $contrato->modalidade_id,
                    'numero_ano' => $contrato->numero,
                    'tipo_empenhopor' => $tipo_empenhopor->id,
                    'fornecedor_compra_id' => $contrato->fornecedor_id,
                    'numero_contrato' => $contrato->numero,
                ]);

                DB::commit();

                return redirect('/empenho/fornecedor/' . $minutaEmpenho->id);
            } catch (Exception $exc) {
                DB::rollback();
            }
        }

        //COMPRA
        if ($request->tipoEmpenho == 2) {
            $retornoSiasg = $this->consultaCompraSiasg($request);

            if (is_null($retornoSiasg->data)) {
                $compra = $this->verificaCompraExiste($request);

                if (!$compra) {
                    return redirect('/empenho/buscacompra')->with('alert-warning', $retornoSiasg->messagem);
                }

                $retornoSiasg = $compra;
            }
            //    $unidade_autorizada_id = '6625';
            $unidade_autorizada_id = $this->verificaPermissaoUasgCompra($retornoSiasg, $request);

            if (is_null($unidade_autorizada_id)) {
                return redirect('/empenho/buscacompra')
                    ->with('alert-warning', 'Você não tem permissão para realizar empenho para esta unidade!');
            }

            $this->montaParametrosCompra($retornoSiasg, $request);

            $situacao = Codigoitem::wherehas('codigo', function ($q) {
                $q->where('descricao', '=', 'Situações Minuta Empenho');
            })
                ->where('descricao', 'EM ANDAMENTO')
                ->first();

            DB::beginTransaction();

            try {
                $compra = $this->updateOrCreateCompra($request);

                if ($retornoSiasg->data->compraSispp->tipoCompra == 1) {
                    $this->gravaParametroItensdaCompraSISPP($retornoSiasg, $compra);
                }

                if ($retornoSiasg->data->compraSispp->tipoCompra == 2) {
                    $this->gravaParametroItensdaCompraSISRP($retornoSiasg, $compra);
                }

                $tipo_empenhopor = Codigoitem::where('descricao', '=', 'Compra')
                    ->where('descres', 'COM')
                    ->first();

                $minutaEmpenho = $this->gravaMinutaEmpenho([
                    'situacao_id' => $situacao->id,
                    'compra_id' => $compra->id,
                    'contrato_id' => null,
                    'unidade_origem_id' => $compra->unidade_origem_id,
                    'unidade_id' => $unidade_autorizada_id,
                    'modalidade_id' => $compra->modalidade_id,
                    'numero_ano' => $compra->numero_ano,
                    'tipo_empenhopor' => $tipo_empenhopor->id
                ]);

                DB::commit();

                return redirect('/empenho/fornecedor/' . $minutaEmpenho->id);
            } catch (Exception $exc) {
                DB::rollback();
            }
        }

        //SUPRIMENTO
        if ($request->tipoEmpenho == 3) {
            DB::beginTransaction();
            try {
                $modadalidade_id = $this->retornaIdCodigoItem('Modalidade Licitação', 'Suprimento de Fundos');
                $tipo_compra_id = $this->retornaIdCodigoItem('Tipo Compra', 'SISPP');
                $request->request->set('unidade_origem_id', session('user_ug_id'));
                $request->request->set('modalidade_id', $modadalidade_id);
                $request->request->set('numero_ano', '99999/9999');
                $request->request->set('tipo_compra_id', $tipo_compra_id);
                $request->request->set('unidade_subrrogada_id', null);
                $request->request->set('inciso', null);
                $request->request->set('lei', null);

                $compra = $this->updateOrCreateCompra($request);
                $this->gravaParametrosSuprimento($compra, $request->fornecedor_empenho_id);

                $tipo_empenhopor_id = $this->retornaIdCodigoItem('Tipo Empenho Por', 'Suprimento');

                $situacao_id = $this->retornaIdCodigoItem('Situações Minuta Empenho', 'EM ANDAMENTO');
                $minutaEmpenho = $this->gravaMinutaEmpenho([
                    'situacao_id' => $situacao_id,
                    'compra_id' => $compra->id,
                    'contrato_id' => null,
                    'unidade_origem_id' => $compra->unidade_origem_id,
                    'unidade_id' => session('user_ug_id'),
                    'modalidade_id' => $compra->modalidade_id,
                    'numero_ano' => $compra->numero_ano,
                    'tipo_empenhopor' => $tipo_empenhopor_id,
                    'fornecedor_empenho_id' => $request->fornecedor_empenho_id,
                ]);

                DB::commit();


                return redirect(route(
                    'empenho.minuta.etapa.item',
                    ['minuta_id' => $minutaEmpenho->id, 'fornecedor_id' => $request->fornecedor_empenho_id]
                ));
            } catch (Exception $exc) {
                DB::rollback();
            }
        }
    }


    public function updateOrCreateCompra($request)
    {
        $compra = Compra::updateOrCreate(
            [
                'unidade_origem_id' => (int)$request->get('unidade_origem_id'),
                'modalidade_id' => (int)$request->get('modalidade_id'),
                'numero_ano' => $request->get('numero_ano'),
                'tipo_compra_id' => $request->get('tipo_compra_id')
            ],
            [
                'unidade_subrrogada_id' => $request->get('unidade_subrrogada_id'),
                'tipo_compra_id' => $request->get('tipo_compra_id'),
                'inciso' => $request->get('inciso'),
                'lei' => $request->get('lei')
            ]
        );
        return $compra;
    }


    public function consultaCompraSiasg(Request $request)
    {
        $modalidade = Codigoitem::find($request->modalidade_id);
        $uasgCompra = Unidade::find($request->unidade_origem_id);
        $numero_ano = explode('/', $request->get('numero_ano'));
        $apiSiasg = new ApiSiasg();

        $params = [
            'modalidade' => $modalidade->descres,
            'numeroAno' => $numero_ano[0] . $numero_ano[1],
            'uasgCompra' => $uasgCompra->codigo,
            'uasgUsuario' => session('user_ug')
        ];

        $compra = json_decode($apiSiasg->executaConsulta('COMPRASISPP', $params));

        return $compra;
    }

    public function consultaContratoSiasg($contrato)
    {

        $apiSiasg = new ApiSiasg();
        $tipo = Codigoitem::find($contrato->tipo_id);
        $numero_ano = explode('/', $contrato->numero);
        $uasgCompra = Unidade::find($contrato->unidadeorigem_id);

        $dado = [
            'contrato' => $uasgCompra->codigosiasg . $tipo->descres . $numero_ano[0] . $numero_ano[1]
        ];

        $dados = json_decode($apiSiasg->executaConsulta('dadoscontrato', $dado));

        return $dados;
    }

    public function verificaPermissaoUasgCompra($compraSiasg, $request)
    {
        $unidade_autorizada_id = null;
        $tipoCompra = $compraSiasg->data->compraSispp->tipoCompra;
        $subrrogada = $compraSiasg->data->compraSispp->subrogada;
        if ($tipoCompra == $this::SISPP) {
            if ($subrrogada != '000000') {
                ($subrrogada == session('user_ug')) ? $unidade_autorizada_id = session('user_ug_id') : '';
            } else {
                ($request->unidade_origem_id == session('user_ug_id'))
                    ? $unidade_autorizada_id = $request->unidade_origem_id : '';
            }
        } else {
            $unidade_autorizada_id = session('user_ug_id');
        }

        return $unidade_autorizada_id;
    }

    public function verificaPermissaoUasgCompraParamContrato($contrato)
    {
        $unidade_autorizada = null;
        $uasgContrato = Unidade::find($contrato->unidade_id);

        ($uasgContrato->id == session('user_ug_id')) ? $unidade_autorizada = session('user_ug_id') : '';

        return $unidade_autorizada;
    }

    public function montaParametrosCompra($compraSiasg, $request): void
    {

        $unidade_subrogada = $compraSiasg->data->compraSispp->subrogada;
        $request->request->set(
            'unidade_subrrogada_id',
            ($unidade_subrogada <> '000000') ? (int)$this->buscaIdUnidade($unidade_subrogada) : null
        );
        $request->request->set('tipo_compra_id', $this->buscaTipoCompra($compraSiasg->data->compraSispp->tipoCompra));
        $request->request->set('inciso', $compraSiasg->data->compraSispp->inciso);
        $request->request->set('lei', $compraSiasg->data->compraSispp->lei);
    }


    public function buscaTipoCompra($descres)
    {
        $tipocompra = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Tipo Compra');
        })
            ->where('descres', '0' . $descres)
            ->first();
        return $tipocompra->id;
    }

    public function verificaCompraExiste($request)
    {
        $compra = Compra::where('unidade_origem_id', $request->get('unidade_origem_id'))
            ->where('modalidade_id', $request->get('modalidade_id'))
            ->where('numero_ano', $request->get('numero_ano'))
            ->where('tipo_compra_id', $request->get('tipo_compra_id'))
            ->first();

        return $compra;
    }

    public function verificaCompraExisteParamContrato($contrato)
    {
        $uasgCompra = Unidade::find($contrato->unidadeorigem_id);

        $compra = Compra::where('unidade_origem_id', $uasgCompra->id)
            ->where('modalidade_id', $contrato->modalidade_id)
            ->where('numero_ano', $contrato->numero)
            // ->where('tipo_compra_id', $contrato->get('tipo_compra_id'))
            ->first();

        return $compra;
    }


    public function gravaMinutaEmpenho($params)
    {
        $minutaEmpenho = new MinutaEmpenho();
        $minutaEmpenho->unidade_id = $params['unidade_id'];
        $minutaEmpenho->compra_id = $params['compra_id'];
        $minutaEmpenho->contrato_id = $params['contrato_id'];
        $minutaEmpenho->situacao_id = $params['situacao_id'];
        $minutaEmpenho->informacao_complementar = $this->retornaInfoComplementar($params);
        $minutaEmpenho->tipo_empenhopor_id = $params['tipo_empenhopor'];
        $etapa = 2;

        if (isset($params['fornecedor_compra_id'])) {
            $minutaEmpenho->fornecedor_empenho_id = $params['fornecedor_compra_id'];
            $minutaEmpenho->numero_contrato = $params['numero_contrato'];
            $etapa = 3;
        }

        $minutaEmpenho->etapa = $etapa;

        $minutaEmpenho->save();
        return $minutaEmpenho;
    }

    public function retornaInfoComplementar($params)
    {
        $numeroAno = str_replace("/", "", $params['numero_ano']);
        $modalide = Codigoitem::find($params['modalidade_id']);
        $unidade = Unidade::find($params['unidade_id']);
        return $unidade->codigo . $modalide->descres . $numeroAno;
    }

    public function update(UpdateRequest $request)
    {
        $this->setRequestFaixa($request);
        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }
}
