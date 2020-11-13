<?php

namespace App\Http\Controllers\Empenho;

use Alert;

use App\Http\Traits\Formatador;
use App\Models\Catmatseritem;
use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\CompraItemFornecedor;
use App\Models\CompraItemUnidade;
use App\Models\Fornecedor;
use App\Models\MinutaEmpenho;
use App\Models\Unidade;
use App\XML\ApiSiasg;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CompraSiasgRequest as StoreRequest;
use App\Http\Requests\CompraSiasgRequest as UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Route;

class CompraSiasgCrudController extends CrudController
{
    use Formatador;

    public const MATERIAL = [149, 194];
    public const SERVICO = [150, 195];
    public const SISPP = 1;
    public const SISRP = 2;

//    const TIPOCOMPRASIASG =

    public function setup()
    {
        $modalidades = Codigoitem::where('codigo_id', 13)
            ->where('visivel', true)
            ->pluck('descricao', 'id')
            ->toArray();


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

    private function fields(array $modalidades): void
    {
        $this->setFieldModalidade($modalidades);
        $this->setFieldNumeroAno();
        $this->setFieldUnidadeCompra();
    }

    private function setFieldModalidade($modalidade): void
    {
        $this->crud->addField([
            'name' => 'modalidade_id',
            'label' => "Modalidade Licitação",
            'type' => 'select2_from_array',
            'options' => $modalidade,
            'allows_null' => true
        ]);
    }

    private function setFieldNumeroAno(): void
    {
        $this->crud->addField([
            'name' => 'numero_ano',
            'label' => 'Numero / Ano',
            'type' => 'numcontrato'
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
            'attribute2' => "nomeresumido",
            'process_results_template' => 'gescon.process_results_unidade',
            'model' => "App\Models\Unidade",
            'data_source' => url("api/unidade"),
            'placeholder' => "Selecione a Unidade",
            'minimum_input_length' => 2
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
        $retornoSiasg = $this->consultaCompraSiasg($request);

        if (is_null($retornoSiasg->data)) {
            return redirect('/empenho/buscacompra')->with('alert-warning', 'Nenhuma compra foi encontrada!!');
        }

        $unidade_autorizada_id = $this->verificaPermissaoUasgCompra($retornoSiasg, $request);

        if (session()->get('user_ug_id') <> $request->unidade_origem_id) {
            return redirect('/empenho/buscacompra')
                ->with('alert-warning', 'Você não tem permissão para realizar empenho para este unidade!');
        }

        if (is_null($unidade_autorizada_id)) {
            return redirect('/empenho/buscacompra')
                ->with('alert-warning', 'Você não tem permissão para realizar empenho para este unidade Subrogada!');
        }

        $this->montaParametrosCompra($retornoSiasg, $request);

        $compra = $this->verificaCompraExiste($request);

        $situacao = Codigoitem::wherehas('codigo', function ($q) {
            $q->where('descricao', '=', 'Situações Minuta Empenho');
        })
            ->where('descricao', 'EM ANDAMENTO')
            ->first();

        DB::beginTransaction();
        try {
            if (empty($compra)) {
                $redirect_location = parent::storeCrud($request);
                $params['compra_id'] = $this->crud->entry->id;
                $params['unidade_autorizada_id'] = $this->crud->entry->unidade_origem_id;

                if($retornoSiasg->data->compraSispp->tipoCompra == 1){
                    $this->gravaParametroItensdaCompraSISPP($retornoSiasg, $params);
                    $params['unidade_origem_id'] = $this->crud->entry->unidade_origem_id;
                }

                if($retornoSiasg->data->compraSispp->tipoCompra == 2){
                    $this->gravaParametroItensdaCompraSISRP($retornoSiasg, $params);
                    $params['unidade_origem_id'] = $this->crud->entry->unidade_origem_id;
                }

            } else {
                $params['compra_id'] = $compra->id;
                $params['unidade_origem_id'] = $compra->unidade_origem_id;
            }

            $minutaEmpenho = $this->gravaMinutaEmpenho([
                'situacao_id' => $situacao->id,
                'compra_id' => $params['compra_id'],
                'unidade_origem_id' => $params['unidade_origem_id'],
                'modalidade_id' => $request->modalidade_id,
                'numero_ano' => $request->numero_ano
            ]);

            DB::commit();

            return redirect('/empenho/fornecedor/' . $minutaEmpenho->id);
        } catch (Exception $exc) {
            DB::rollback();
        }
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

    public function verificaPermissaoUasgCompra($compraSiasg, $request)
    {
        $unidade_autorizada_id = null;
        $tipoCompra = $compraSiasg->data->compraSispp->tipoCompra;
        $subrrogada = $compraSiasg->data->compraSispp->subrogada;
        if ($tipoCompra == $this::SISPP) {
            if ($subrrogada <> '000000') {
                ($subrrogada == session('user_ug')) ? $unidade_autorizada_id = $subrrogada : '';
            } else {
                ($request->unidade_origem_id == session('user_ug_id'))
                    ? $unidade_autorizada_id = $request->unidade_origem_id : '';
            }
        } else {
            if ($subrrogada <> '000000') {
                ($subrrogada == session('user_ug')) ? $unidade_autorizada_id = $subrrogada : '';
            } else {
                ($request->unidade_origem_id == session('user_ug_id'))
                    ? $unidade_autorizada_id = $request->unidade_origem_id : '';
            }
        }
        return $unidade_autorizada_id;
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

    public function buscaIdUnidade($uasg)
    {
        return Unidade::where('codigo', $uasg)->first()->id;
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
        return Compra::where('unidade_origem_id', $request->get('unidade_origem_id'))
            ->where('modalidade_id', $request->get('modalidade_id'))
            ->where('numero_ano', $request->get('numero_ano'))
            ->where('tipo_compra_id', $request->get('tipo_compra_id'))
            ->first();
    }

    public function gravaParametroItensdaCompraSISPP($compraSiasg, $params): void
    {
        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg, $params);

        DB::beginTransaction();
        try {
            if (!is_null($compraSiasg->data->itemCompraSisppDTO)) {
                foreach ($compraSiasg->data->itemCompraSisppDTO as $key => $item) {

                    $catmatseritem = $this->gravaCatmatseritem($item);

                    $compraItem = new CompraItem();
                    $compraitem_id = $compraItem->gravaCompraItemSispp($params,$catmatseritem,$item);
                    $fornecedor = $this->retornaFornecedor($item);

                    $this->gravaCompraItemFornecedor($compraitem_id,$item,$fornecedor);

                    $this->gravaCompraItemUnidadeSispp($compraitem_id,$item,$unidade_autorizada_id,$fornecedor);

                    DB::commit();
                }
            }
        } catch (Exception $exc) {
            DB::rollback();
        }
    }



    public function gravaParametroItensdaCompraSISRP($compraSiasg, $params): void
    {
        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg, $params);

        DB::beginTransaction();
        try {
            if (!is_null($compraSiasg->data->itemCompraSisppDTO)) {
                foreach ($compraSiasg->data->itemCompraSisppDTO as $key => $item) {
                    dd($item);
                    $catmatseritem = $this->gravaCatmatseritem($item);

                    $compraItem = new CompraItem();
                    $compraitem_id = $compraItem->gravaCompraItemSispp($params,$catmatseritem,$item);

                    $fornecedor = $this->retornaFornecedor($item);
                    $this->gravaCompraItemFornecedor($compraitem_id,$item,$fornecedor);
                    $this->gravaCompraItemUnidadeSisrp($compraitem_id,$item,$unidade_autorizada_id,$fornecedor);

                    DB::commit();
                }
            }
        } catch (Exception $exc) {
            DB::rollback();
        }
    }



    public function gravaCompraItemFornecedor($compraitem_id,$item,$fornecedor)
    {
        $paramsFornecedor['compra_item_id'] = $compraitem_id;
        $paramsFornecedor['fornecedor_id'] = $fornecedor->id;
        $paramsFornecedor['ni_fornecedor'] = $fornecedor->cpf_cnpj_idgener;
        $paramsFornecedor['classificacao'] = (isset($item->classicacao))? $item->classicacao :'';
        $paramsFornecedor['situacao_sicaf'] = $item->situacaoSicaf;
        $paramsFornecedor['quantidade_homologada_vencedor'] = (isset($item->quantidadeHomologadaVencedor))? $item->quantidadeHomologadaVencedor :0;;
        $paramsFornecedor['valor_unitario'] = $item->valorUnitario;
        $paramsFornecedor['valor_negociado'] = $item->valorTotal;
        $paramsFornecedor['quantidade_empenhada'] = (isset($item->quantidadeEmpenhada))? $item->quantidadeEmpenhada :0;
        CompraItemFornecedor::insert($paramsFornecedor);
    }

    public function gravaCompraItemUnidadeSispp($compraitem_id,$item,$unidade_autorizada_id,$fornecedor)
    {
        $paramsUnidade['compra_item_id'] = $compraitem_id;
        $paramsUnidade['unidade_id'] = $unidade_autorizada_id;;
        $paramsUnidade['fornecedor_id'] = $fornecedor->id;
        $paramsUnidade['quantidade_saldo'] = $item->quantidadeTotal;
        $paramsUnidade['quantidade_total'] = $item->quantidadeTotal;
        CompraItemUnidade::insert($paramsUnidade);
    }

    public function gravaCompraItemUnidadeSisrp($compraitem_id,$item,$unidade_autorizada_id,$fornecedor)
    {
        $paramsUnidade['compra_item_id'] = $compraitem_id;
        $paramsUnidade['unidade_id'] = $unidade_autorizada_id;;
        $paramsUnidade['fornecedor_id'] = $fornecedor->id;
        $paramsUnidade['quantidade_autorizada'] = 0;
        $paramsUnidade['quantidade_saldo'] = $item->quantidadeTotal;
        $paramsUnidade['tipo_uasg'] = '';
        $paramsUnidade['quantidade_total'] = $item->quantidadeTotal;
        $paramsUnidade['quantidade_adquirir'] = 0;
        $paramsUnidade['quantidade_adquirir'] = 0;
        CompraItemUnidade::insert($paramsUnidade);

    }

    public function gravaCatmatseritem($item)
    {
        $tipo = ['S' => $this::SERVICO[0], 'M' => $this::MATERIAL[0]];
        $catGrupo = ['S' => $this::SERVICO[1], 'M' => $this::MATERIAL[1]];
        $catmatseritem = Catmatseritem::updateOrCreate(
            ['codigo_siasg' => (int)$item->codigo],
            ['descricao' => $item->descricao, 'grupo_id' => $catGrupo[$item->tipo]]
        );
        return $catmatseritem;
    }

    public function retornaUnidadeAutorizada($compraSiasg, $params)
    {

        $unidade_autorizada_id = null;
        $tipoCompra = $compraSiasg->data->compraSispp->tipoCompra;
        $subrrogada = $compraSiasg->data->compraSispp->subrogada;
        if ($tipoCompra == $this::SISPP) {
            ($subrrogada <> '000000')
                ? $unidade_autorizada_id = (int)$this->buscaIdUnidade($subrrogada)
                : $unidade_autorizada_id = $params['unidade_autorizada_id'];
        }
        if ($tipoCompra == $this::SISRP) {
            //tratar unidade autorizada SISRP - Aguardando Serviço ficar pronto
        }

        return $unidade_autorizada_id;
    }

    public function retornaFornecedor($item)
    {
        $fornecedor = new Fornecedor();
        $retorno = $fornecedor->buscaFornecedorPorNumero($item->niFornecedor);

        //TODO UPDATE OR INSERT FORNECEDOR
        if (is_null($retorno)) {
            $fornecedor->tipo_fornecedor = $fornecedor->retornaTipoFornecedor($item->niFornecedor);
            $fornecedor->cpf_cnpj_idgener = $fornecedor->formataCnpjCpf($item->niFornecedor);
            $fornecedor->nome = $item->nomeFornecedor;
            $fornecedor->save();
            return $fornecedor;
        }
        return $retorno;
    }

    public function gravaMinutaEmpenho($params)
    {
        $minutaEmpenho = new MinutaEmpenho();
        $minutaEmpenho->unidade_id = $params['unidade_origem_id'];
        $minutaEmpenho->compra_id = $params['compra_id'];
        $minutaEmpenho->situacao_id = $params['situacao_id'];
        $minutaEmpenho->etapa = 2;
        $minutaEmpenho->informacao_complementar = $this->retornaInfoComplementar($params);

        $minutaEmpenho->save();
        return $minutaEmpenho;
    }

    public function retornaInfoComplementar($params)
    {
        $numeroAno = str_replace("/", "", $params['numero_ano']);
        $modalide = Codigoitem::find($params['modalidade_id']);
        $unidade = Unidade::find($params['unidade_origem_id']);
        $info = $unidade->codigo . $modalide->descres . $numeroAno;

        return $info;
    }

    public function update(UpdateRequest $request)
    {
        $this->setRequestFaixa($request);
        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }
}
