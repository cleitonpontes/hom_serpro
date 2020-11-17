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
use App\Models\CompraItemMinutaEmpenho;
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
use App\Http\Traits\CompraTrait;

class CompraSiasgCrudController extends CrudController
{
    use Formatador;
    use CompraTrait;

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
        //todo verificar se pode empenhar para outra unidade não logada
        //https://hom.siasgnet-consultas.siasgnet.estaleiro.serpro.gov.br/siasgnet-externo/compra/v1/sisrp?uasgUsuario=090026&uasgCompra=090026&modalidade=05&numeroAnoCompra=000232019&numeroItem=00001&tipoUASG=G

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
            $compra = $this->updateOrCreateCompra($request);

            if ($retornoSiasg->data->compraSispp->tipoCompra == 1) {
                $this->gravaParametroItensdaCompraSISPP($retornoSiasg, $compra);
            }

            if ($retornoSiasg->data->compraSispp->tipoCompra == 2) {
                $this->gravaParametroItensdaCompraSISRP($retornoSiasg, $compra);
            }


            $minutaEmpenho = $this->gravaMinutaEmpenho([
                'situacao_id' => $situacao->id,
                'compra_id' => $compra->id,
                'unidade_origem_id' => $compra->unidade_origem_id,
                'modalidade_id' => $compra->modalidade_id,
                'numero_ano' => $compra->numero_ano
            ]);

            DB::commit();

            return redirect('/empenho/fornecedor/' . $minutaEmpenho->id);
        } catch (Exception $exc) {
            DB::rollback();
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

    public function updateOrCreateCompraItemSispp($compra, $catmatseritem, $item)
    {
        $tipo = ['S' => $this::SERVICO[0], 'M' => $this::MATERIAL[0]];

        $compraitem = CompraItem::updateOrCreate(
            [
                'compra_id' => (int)$compra->id,
                'tipo_item_id' => (int)$tipo[$item->tipo],
                'catmatseritem_id' => (int)$catmatseritem->id,
                'numero' => (string)$item->numero,
            ],
            [
                'descricaodetalhada' => (string)$item->descricaoDetalhada,
                'qtd_total' => $item->quantidadeTotal
            ]
        );
        return $compraitem;
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
        $compra = Compra::where('unidade_origem_id', $request->get('unidade_origem_id'))
            ->where('modalidade_id', $request->get('modalidade_id'))
            ->where('numero_ano', $request->get('numero_ano'))
            ->where('tipo_compra_id', $request->get('tipo_compra_id'))
            ->first();

        return $compra;
    }

    public function gravaParametroItensdaCompraSISPP($compraSiasg, $compra): void
    {
        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg, $compra);

        if (!is_null($compraSiasg->data->itemCompraSisppDTO)) {
            foreach ($compraSiasg->data->itemCompraSisppDTO as $key => $item) {
                $catmatseritem = $this->gravaCatmatseritem($item);

                $compraitem = $this->updateOrCreateCompraItemSispp($compra, $catmatseritem, $item);

                $fornecedor = $this->retornaFornecedor($item);

                $this->gravaCompraItemFornecedor($compraitem->id, $item, $fornecedor);

                $this->gravaCompraItemUnidadeSispp($compraitem->id, $item, $unidade_autorizada_id, $fornecedor);
            }
        }
    }


    public function gravaParametroItensdaCompraSISRP($compraSiasg, $compra): void
    {
        $unidade_autorizada_id = $this->retornaUnidadeAutorizada($compraSiasg, $compra);
        $consultaCompra = new ApiSiasg();

        DB::beginTransaction();
        try {
            if (!is_null($compraSiasg->data->linkSisrpCompleto)) {
                foreach ($compraSiasg->data->linkSisrpCompleto as $key => $item) {
                    $dadosItemCompra = ($consultaCompra->consultaCompraByUrl($item->linkSisrpCompleto));
                    $tipoUasg = (substr($item->linkSisrpCompleto, -1));
                    $dadosata = (object)$dadosItemCompra['data']['dadosAta'];
                    $gerenciadoraParticipante = (object)$dadosItemCompra['data']['dadosGerenciadoraParticipante'];
                    $carona = $dadosItemCompra['data']['dadosCarona'];
                    $dadosFornecedor = $dadosItemCompra['data']['dadosFornecedor'];

                    $catmatseritem = $this->gravaCatmatseritem($dadosata);

                    $modcompraItem = new CompraItem();
                    $compraItem = $modcompraItem->updateOrCreateCompraItemSisrp($compra, $catmatseritem, $dadosata);

                    foreach ($dadosFornecedor as $key => $itemfornecedor) {
                        $fornecedor = $this->retornaFornecedor((object)$itemfornecedor);

                        $this->gravaCompraItemFornecedor($compraItem->id, (object)$itemfornecedor, $fornecedor);
                    }
                    $this->gravaCompraItemUnidadeSisrp($compraItem, $unidade_autorizada_id, $item, $gerenciadoraParticipante, $carona, $dadosFornecedor, $tipoUasg);

                    DB::commit();
                }
            }
        } catch (Exception $exc) {
            DB::rollback();
        }
    }

    public function gravaCompraItemUnidadeSisrp($compraitem, $unidade_autorizada_id, $item, $dadosGerenciadoraParticipante, $carona, $dadosFornecedor, $tipoUasg)
    {
        $qtd_autorizada = $dadosGerenciadoraParticipante->quantidadeAAdquirir - $dadosGerenciadoraParticipante->quantidadeAdquirida;
        $fornecedor_id = null;
        if (!is_null($carona)) {
            $carona = (object)$carona;
            $qtd_autorizada = $carona->quantidadeAutorizada;
            $fornecedor = $this->retornaFornecedor((object)$dadosFornecedor[0]);
            $fornecedor_id = $fornecedor->id;
        }

        $compraItemUnidade = CompraItemUnidade::updateOrCreate(
            [
                'compra_item_id' => $compraitem->id,
                'unidade_id' => $unidade_autorizada_id,
                'fornecedor_id' => $fornecedor_id,

            ],
            [
                'quantidade_autorizada' => $qtd_autorizada,
                'quantidade_saldo' => $qtd_autorizada,
                'tipo_uasg' => $tipoUasg,
                'quantidade_adquirir' => $dadosGerenciadoraParticipante->quantidadeAAdquirir,
                'quantidade_adquirida' => $dadosGerenciadoraParticipante->quantidadeAdquirida
            ]
        );

        $saldo = $this->retornaSaldoAtualizado($compraitem->id);
        if (isset($saldo->saldo)) {
            $compraItemUnidade->quantidade_saldo = $saldo->saldo;
            $compraItemUnidade->save();
        }
    }

    public function gravaCompraItemFornecedor($compraitem_id, $item, $fornecedor)
    {

        CompraItemFornecedor::updateOrCreate(
            [
                'compra_item_id' => $compraitem_id,
                'fornecedor_id' => $fornecedor->id
            ],
            [
                'ni_fornecedor' => $fornecedor->cpf_cnpj_idgener,
                'classificacao' => (isset($item->classicacao)) ? $item->classicacao : '',
                'situacao_sicaf' => $item->situacaoSicaf,
                'quantidade_homologada_vencedor' => (isset($item->quantidadeHomologadaVencedor)) ? $item->quantidadeHomologadaVencedor : 0,
                'valor_unitario' => $item->valorUnitario,
                'valor_negociado' => (isset($item->valorTotal)) ? $item->valorTotal : $item->valorNegociado,
                'quantidade_empenhada' => (isset($item->quantidadeEmpenhada)) ? $item->quantidadeEmpenhada : 0
            ]
        );
    }

    public function gravaCompraItemUnidadeSispp($compraitem_id, $item, $unidade_autorizada_id, $fornecedor)
    {
//        dump($compraitem_id,$unidade_autorizada_id,$fornecedor->id);
        $compraItemUnidade = CompraItemUnidade::updateOrCreate(
            [
                'compra_item_id' => $compraitem_id,
                'unidade_id' => $unidade_autorizada_id,
                'fornecedor_id' => $fornecedor->id
            ],
            [
                'quantidade_saldo' => $item->quantidadeTotal,
                'quantidade_autorizada' => $item->quantidadeTotal
            ]
        );

        $saldo = $this->retornaSaldoAtualizado($compraitem_id);

        if (isset($saldo->saldo)) {
            $compraItemUnidade->quantidade_saldo = $saldo->saldo;
            $compraItemUnidade->save();
        }
    }

    public function gravaCatmatseritem($item)
    {
        $codigo_siasg = (isset($item->codigo)) ? $item->codigo : $item->codigoItem;
        $tipo = ['S' => $this::SERVICO[0], 'M' => $this::MATERIAL[0]];
        $catGrupo = ['S' => $this::SERVICO[1], 'M' => $this::MATERIAL[1]];
        $catmatseritem = Catmatseritem::updateOrCreate(
            ['codigo_siasg' => (int)$codigo_siasg],
            ['descricao' => $item->descricao, 'grupo_id' => $catGrupo[$item->tipo]]
        );
        return $catmatseritem;
    }

    public function retornaUnidadeAutorizada($compraSiasg, $compra)
    {

        $unidade_autorizada_id = null;
        $tipoCompra = $compraSiasg->data->compraSispp->tipoCompra;
        $subrrogada = $compraSiasg->data->compraSispp->subrogada;
        if ($tipoCompra == $this::SISPP) {
            ($subrrogada <> '000000')
                ? $unidade_autorizada_id = (int)$this->buscaIdUnidade($subrrogada)
                : $unidade_autorizada_id = $compra->unidade_origem_id;
        }
        if ($tipoCompra == $this::SISRP) {
            ($subrrogada <> '000000')
                ? $unidade_autorizada_id = (int)$this->buscaIdUnidade($subrrogada)
                : $unidade_autorizada_id = $compra->unidade_origem_id;
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
