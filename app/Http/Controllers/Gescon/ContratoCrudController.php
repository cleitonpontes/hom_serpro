<?php

namespace App\Http\Controllers\Gescon;

use App\Forms\InserirItemContratoMinutaForm;
use App\Http\Traits\Formatador;
use App\Http\Traits\BuscaCodigoItens;
use App\Jobs\AlertaContratoJob;
use App\Models\AmparoLegalContrato;
use App\Models\Catmatseritem;
use App\Models\Codigoitem;
use App\Models\Comprasitemunidadecontratoitens;
use App\Models\Contrato;
use App\Models\Contratoitem;
use App\Models\ContratoMinutaEmpenho;
use App\Models\MinutaEmpenho;
use App\Models\Fornecedor;
use App\Models\Saldohistoricoitem;
use App\PDF\Pdf;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use FormBuilder;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ContratoRequest as StoreRequest;
use App\Http\Requests\ContratoRequest as UpdateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Request;

// TODO: Apagar classes sem uso
use App\Models\Contratohistorico;
use App\Models\Contratoresponsavel;
use App\Models\Unidade;
use App\Notifications\RotinaAlertaContratoNotification;
use App\XML\ApiSiasg;
use Backpack\CRUD\CrudPanel;
use Codedge\Fpdf\Fpdf\Fpdf;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class ContratoCrudController
 *
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanell $crud
 */
class ContratoCrudController extends CrudController
{
    use Formatador;
    use BuscaCodigoItens;

    protected $tab = '';

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Contrato');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/gescon/contrato');
        $this->crud->setEntityNameStrings('Contrato', 'Contratos');
        $this->crud->setCreateContentClass('col-md-12');
        $this->crud->setEditContentClass('col-md-12');
        $this->crud->setEditView('vendor.backpack.crud.contrato.create');
        $this->crud->addClause('join', 'fornecedores', 'fornecedores.id', '=', 'contratos.fornecedor_id');
        $this->crud->addClause('join', 'unidades', 'unidades.id', '=', 'contratos.unidade_id');
        $this->crud->addClause('where', 'unidade_id', '=', session()->get('user_ug_id'));
        $this->crud->orderBy('updated_at', 'desc');
        $this->crud->addClause('select', 'contratos.*');

        // $this->crud->addButtonFromView('top', 'notificausers', 'notificausers', 'end');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration Global
        |--------------------------------------------------------------------------
        */

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->enableExportButtons();
        // $this->crud->disableResponsiveTable();

        // $this->crud->addButtonFromView('top', 'siasg', 'siasg', 'end');
        $this->crud->addButtonFromView('line', 'extratocontrato', 'extratocontrato', 'beginning');
        $this->crud->addButtonFromView('line', 'delete', 'delete_contrato', 'end');
        $this->crud->addButtonFromView('line', 'morecontrato', 'morecontrato', 'end');
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('delete');
        $this->crud->allowAccess('show');

        (backpack_user()->can('contrato_inserir')) ? $this->crud->allowAccess('create') : null;
        (backpack_user()->can('contrato_deletar')) ? $this->crud->allowAccess('delete') : null;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Custom
        |--------------------------------------------------------------------------
        */

        $this->adicionaCampos();
        $this->adicionaColunas();
        $this->aplicaFiltros();
    }

    public function store(StoreRequest $request)
    {

        $valor_parcela = $request->input('valor_parcela');
        $request->request->set('valor_parcela', $valor_parcela);

        $valor_global = $request->input('valor_global');
        $request->request->set('valor_global', $valor_global);
        $request->request->set('valor_inicial', $valor_global);

        // Caso tenha empenho preenchido utilizar os campos de unidade, modalidade e numero da licitacao de acordo
        // com a compra da minuta de empenho descartando os valores inseridos pelo usu??rio
        if (!empty($request->get('minutasempenho'))) {
            $camposBaseadosEmpenho = $this->buscarCamposBaseadosEmpenho(current($request->get('minutasempenho')));
            $request->request->set('unidadecompra_id', $camposBaseadosEmpenho['unidade_id']);
            $request->request->set('modalidade_id', $camposBaseadosEmpenho['modalidade_id']);
            $request->request->set('licitacao_numero', $camposBaseadosEmpenho['compra_numero_ano']);
        }

        DB::beginTransaction();
        try {
            $redirect_location = parent::storeCrud($request);
            $contrato_id = $this->crud->getCurrentEntryId();

            $request->request->set('contrato_id', $contrato_id);
            if (!empty($request->get('qtd_item'))) {
                $this->inserirItensContrato($request->all());
            }

            if (!empty($request->get('minutasempenho'))) {
                $this->vincularMinutaContratoHistorico($request->all(), $contrato_id);
            }

            if (!empty($request->get('amparoslegais'))) {
                $this->vincularAmparoLegalContratoHistorico($request->all(), $contrato_id);
            }

            DB::commit();
            return redirect()->route('crud.publicacao.index', ['contrato_id'=>$contrato_id]);
        } catch (Exception $exc) {
            DB::rollback();
//            dd($exc);
        }
    }

    private function buscarCamposBaseadosEmpenho($idEmpenho)
    {
        $camposContrato = MinutaEmpenho::select(
            "compras.modalidade_id",
            "minutaempenhos.unidade_id",
            "compras.numero_ano as compra_numero_ano"
        )
            ->join('compras', 'compras.id', '=', 'minutaempenhos.compra_id')
            ->where('minutaempenhos.id', $idEmpenho)->firstOrFail()->toArray();

        return $camposContrato;
    }

    public function inserirItensContrato($request)
    {

        foreach ($request['qtd_item'] as $key => $qtd) {
            $catmatseritem_id = (int)$request['catmatseritem_id'][$key];
            $catmatseritem = Catmatseritem::find($catmatseritem_id);

            $contratoItem = new Contratoitem();
            $contratoItem->contrato_id = $request['contrato_id'];
            $contratoItem->tipo_id = $request['tipo_item_id'][$key];
            $contratoItem->grupo_id = $catmatseritem->grupo_id;
            $contratoItem->catmatseritem_id = $catmatseritem->id;
            $contratoItem->descricao_complementar = $catmatseritem->descricao;
            $contratoItem->quantidade = (double)$qtd;
            $contratoItem->valorunitario = $request['vl_unit'][$key];
            $contratoItem->valortotal = $request['vl_total'][$key];
            $contratoItem->data_inicio = $request['data_inicio'][$key];
            $contratoItem->periodicidade = $request['periodicidade'][$key];
            $contratoItem->numero_item_compra = $request['numero_item_compra'][$key];
            $contratoItem->save();
            if ($request['compra_item_unidade_id'][$key] !== 'undefined') {
                $this->vincularContratoItensCompraItemUnidade($contratoItem, $request['compra_item_unidade_id'][$key]);
            }
        }
    }

    /**
     *  Ao gravar o contrato gravar as minutas para contratoHistorico na tabela pivot
     *
     * @param $request
     * @param $contrato_id
     */
    private function vincularMinutaContratoHistorico($request, $contrato_id)
    {
        $contratoHistorico = Contratohistorico::where('contrato_id', '=', $contrato_id)->first();

        // vincula os empenhos ao contrato historico
        foreach ($request['minutasempenho'] as $MinutaEmpenhoId) {
            $contratoHistorico->minutasempenho()->attach($MinutaEmpenhoId);
        }
    }

    /**
     * Ao gravar o contrato gravar o amparo legal para contratoHistorico na tabela pivot
     *
     * @param $request
     * @param $contrato_id
     */

    private function vincularAmparoLegalContratoHistorico($request, $contrato_id)
    {
        $contratoHistorico = Contratohistorico::where('contrato_id', '=', $contrato_id)->first();

        // vincula os empenhos ao contrato historico
        foreach ($request['amparoslegais'] as $amparoLegalId) {
            $contratoHistorico->amparolegal()->attach($amparoLegalId);
        }
    }

    public function vincularContratoItensCompraItemUnidade($contratoItem, $compra_item_unidade_id)
    {
        $compraItemUnidade_ContratoItem = new Comprasitemunidadecontratoitens();
        $compraItemUnidade_ContratoItem->contratoitem_id = $contratoItem->id;
        $compraItemUnidade_ContratoItem->compra_item_unidade_id = $compra_item_unidade_id;
        $compraItemUnidade_ContratoItem->save();
    }

    public function update(UpdateRequest $request)
    {

        $valor_parcela = str_replace(',', '.', str_replace('.', '', $request->input('valor_parcela')));
        $request->request->set('valor_parcela', number_format(floatval($valor_parcela), 2, '.', ''));

        $valor_global = str_replace(',', '.', str_replace('.', '', $request->input('valor_global')));
        $request->request->set('valor_global', number_format(floatval($valor_global), 2, '.', ''));

        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }

    public function show($id)
    {
        $content = parent::show($id);

        $this->crud->removeColumn('fornecedor_id');
        $this->crud->removeColumn('tipo_id');
        $this->crud->removeColumn('categoria_id');
        $this->crud->removeColumn('unidade_id');
        $this->crud->removeColumn('info_complementar');
        $this->crud->removeColumn('fundamento_legal');
        $this->crud->removeColumn('modalidade_id');
        $this->crud->removeColumn('licitacao_numero');
        $this->crud->removeColumn('data_assinatura');
        $this->crud->removeColumn('data_publicacao');
        $this->crud->removeColumn('valor_inicial');
        $this->crud->removeColumn('valor_global');
        $this->crud->removeColumn('valor_parcela');
        $this->crud->removeColumn('valor_acumulado');
        $this->crud->removeColumn('situacao_siasg');
        $this->crud->removeColumn('receita_despesa');
        $this->crud->removeColumn('subcategoria_id');

        return $content;
    }

    public function extratoPdf(int $contrato_id)
    {
        $contrato = Contrato::find($contrato_id);

        $pdf = new Pdf("P", "mm", "A4");
        $pdf->SetTitle("Extrato Contrato", 1);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Dados do contratos
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(0, 5, utf8_decode("Dados do Contrato") . ' - Contrato num.: ' . utf8_decode($contrato->numero) . ' - UG: ' . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido), 0, 0, 'C');

        $pdf->SetY("35");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(31, 5, utf8_decode("N??mero do instrumento: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 5, utf8_decode($contrato->numero), 0, 0, 'L');

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 5, utf8_decode("Fornecedor: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(18, 5, utf8_decode(strlen($contrato->fornecedor->nome) > 65 ? substr($contrato->fornecedor->nome, 0, 65) . " [...]" : $contrato->fornecedor->nome), 0, 0, 'L');

        $pdf->SetY("40");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(33, 5, utf8_decode("CNPJ/CPF/ID Gen??rico: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode($contrato->fornecedor->cpf_cnpj_idgener), 0, 0, 'L');


        $pdf->SetY("45");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(22, 5, utf8_decode("Processo N??m.: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, utf8_decode($contrato->processo), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(18, 5, utf8_decode("UG Recurso: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nome), 0, 0, 'L');

        $pdf->SetY("50");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(23, 5, utf8_decode("Data Assinatura: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 5, utf8_decode(implode("/", array_reverse(explode("-", $contrato->data_assinatura)))), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(23, 5, utf8_decode("Tipo do Contrato: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode($contrato->tipo->descricao), 0, 0, 'L');

        $pdf->SetY("55");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(23, 5, utf8_decode("Tipo Licita????o: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 5, utf8_decode($contrato->modalidade->descricao), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, utf8_decode("N??mero Licita????o: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20, 5, utf8_decode($contrato->licitacao_numero), 0, 0, 'L');

        $pdf->SetY("60");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 5, utf8_decode("Data Vig??ncia In??cio: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 5, utf8_decode(implode("/", array_reverse(explode("-", $contrato->vigencia_inicio)))), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 5, utf8_decode("Data Vig??ncia Fim: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 5, utf8_decode(implode("/", array_reverse(explode("-", $contrato->vigencia_fim)))), 0, 0, 'L');

        $pdf->SetY("65");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(17, 5, utf8_decode("Valor Global: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode(number_format($contrato->valor_global, 2, ',', '.')), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(21, 5, utf8_decode("N??m. Parcelas: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(10, 5, utf8_decode(number_format($contrato->num_parcelas, 0, '', '.')), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(18, 5, utf8_decode("Valor Parcial: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode(number_format($contrato->valor_parcela, 2, ',', '.')), 0, 0, 'L');

        $pdf->SetY("70");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(24, 5, utf8_decode("Valor Acumulado: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode(number_format($contrato->valor_acumulado, 2, ',', '.')), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(33, 5, utf8_decode("Total Desp. Acess??rias: "), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 5, utf8_decode(number_format($contrato->total_despesas_acessorias, 2, ',', '.')), 0, 0, 'L');

        $pdf->SetY("75");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode("Objeto: "), 0, 0, 'L');
        $pdf->SetY("80");
        $pdf->SetFont('Arial', 'B', 9);
        //$pdf->MultiCell(0, 5, utf8_decode(preg_replace( "/\r|\n/", "", $contrato->objeto )), 0, 'J');
        $pdf->MultiCell(0, 5, utf8_decode($contrato->objeto), 0, 'J');

        //numero de caracteres fonte 9 por linha 100

        $pdf->SetY(80 + ($pdf->NbLines(161, utf8_decode($contrato->objeto)) * 5));
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode("Informa????o Complementar: "), 0, 0, 'L');
        $pdf->SetY(85 + ($pdf->NbLines(161, utf8_decode($contrato->objeto)) * 5));
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(0, 5, utf8_decode($contrato->info_complementar), 0, 'J');

        //Hist??rico de Contrato
        $pdf->AddPage();
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(
            0,
            5,
            utf8_decode("Hist??rico do Contrato") . ' - Contrato num.: '
            . utf8_decode($contrato->numero) . ' - UG: '
            . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido),
            0,
            0,
            'C'
        );
        $cell_width = 23;
        $pdf->SetY(35);
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(0, 5, utf8_decode("Hist??rico"));

        $pdf->SetY(40);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($cell_width, 5, utf8_decode("Tipo"), 1, 0, 'C');

        $pdf->Cell($cell_width, 5, utf8_decode("N??mero"), 1, 0, 'C');
        //$pdf->Cell(21, 5, utf8_decode("Observa????o"), 1, 0, 'C');
        $pdf->Cell($cell_width, 5, utf8_decode("Data Assinatura"), 1, 0, 'C');
        $pdf->Cell($cell_width, 5, utf8_decode("Data In??cio"), 1, 0, 'C');
        $pdf->Cell($cell_width, 5, utf8_decode("Data Fim"), 1, 0, 'C');
        $pdf->Cell($cell_width, 5, utf8_decode("Valor Global"), 1, 0, 'C');
        $pdf->Cell($cell_width, 5, utf8_decode("Parcelas"), 1, 0, 'C');
        $pdf->Cell($cell_width, 5, utf8_decode("Valor Parcela"), 1, 0, 'C');

        $row_resp = 45;
        $historico = $contrato->historico()->get();

        foreach ($historico as $registro) {
            if ($row_resp >= 245) {
                $row_resp = 40;
                $pdf->AddPage();
                $pdf->SetY($row_resp);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell($cell_width, 5, utf8_decode("Tipo"), 1, 0, 'C');
                $pdf->Cell($cell_width, 5, utf8_decode("N??mero"), 1, 0, 'C');
                $pdf->Cell($cell_width, 5, utf8_decode("Data Assinatura"), 1, 0, 'C');
                $pdf->Cell($cell_width, 5, utf8_decode("Data In??cio"), 1, 0, 'C');
                $pdf->Cell($cell_width, 5, utf8_decode("Data Fim"), 1, 0, 'C');
                $pdf->Cell($cell_width, 5, utf8_decode("Valor Global"), 1, 0, 'C');
                $pdf->Cell($cell_width, 5, utf8_decode("Parcelas"), 1, 0, 'C');
                $pdf->Cell($cell_width, 5, utf8_decode("Valor Parcela"), 1, 0, 'C');
                $row_resp += 5;
            }

            $pdf->SetY($row_resp);

            $linhas = $pdf->NbLines($cell_width, utf8_decode(($registro->tipo()->first()->descricao))) * 5;
            $pdf->SetFont('Arial', 'B', 7);
            //A MultiCell quebra a linha atual ap??s ser exibida e ao us??-la fora da ??ltima coluna o ponto XY deve
            //ser atualizado para continuar na linha atual.
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $pdf->MultiCell($cell_width, 5, utf8_decode($registro->tipo()->first()->descricao), 1, 'C');
            $pdf->SetXY($current_x + $cell_width, $current_y);

            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell($cell_width, $linhas, $registro->numero, 1, 0, 'C');
            $pdf->Cell($cell_width, $linhas, implode('/', array_reverse(explode('-', $registro->data_assinatura))), 1, 0, 'C');
            $pdf->Cell($cell_width, $linhas, implode('/', array_reverse(explode('-', $registro->vigencia_inicio))), 1, 0, 'C');
            $pdf->Cell($cell_width, $linhas, implode('/', array_reverse(explode('-', $registro->vigencia_fim))), 1, 0, 'C');
            $pdf->Cell($cell_width, $linhas, number_format($registro->valor_global, 2, ',', "."), 1, 0, 'R');
            $pdf->Cell($cell_width, $linhas, $registro->num_parcelas, 1, 0, 'R');
            $pdf->Cell($cell_width, $linhas, number_format($registro->valor_parcela, 2, ',', "."), 1, 0, 'R');

            $row_resp += $linhas;
            $pdf->SetY($row_resp);

            $linhas = $pdf->NbLines(161, utf8_decode($registro->observacao)) * 5;
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell($cell_width, $linhas, utf8_decode("Observa????o"), 1, 0, 'C');

            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(161, 5, utf8_decode($registro->observacao), 1);

            $row_resp += $linhas + 5;
        }

        //responsaveis do contrato
        //Respons??veis
        $pdf->AddPage();
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(0, 5, utf8_decode("Respons??veis") . ' - Contrato num.: ' . utf8_decode($contrato->numero) . ' - UG: ' . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido), 0, 0, 'C');

        //busca responsaveis por situacao
        $responsaveis_ativos = $contrato->responsaveis()->where('situacao', true)->get();
        //no mapeamento da classe contrato com a classe contratoresponsavel, somente os responsaveis ativos s??o buscados
        $responsaveis_inativos = Contratoresponsavel::where('contrato_id', $contrato->id)->where('situacao', false)->get();

        //ativos
        $pdf->SetY("35");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(28, 5, utf8_decode("Ativos"), 0, 0, 'L');

        $row_resp = 35 + 5;

        foreach ($responsaveis_ativos as $ativo) {
            if ($row_resp >= 260) {
                $row_resp = 35;
                $pdf->AddPage();
            }

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("CPF / Nome: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 5, utf8_decode($ativo->user->cpf . ' - ' . $ativo->user->name), 'T', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Fun????o: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode($ativo->funcao->descricao), 'T', 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Portaria: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 5, utf8_decode($ativo->portaria), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 5, utf8_decode("Telefone Fixo: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(48, 5, utf8_decode($ativo->telefone_fixo), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, 5, utf8_decode("Telefone Celular: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode($ativo->telefone_celular), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Unidade: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(($ativo->instalacao_id) ? $ativo->instalacao->nome : ''), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data In??cio: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode(implode("/", array_reverse(explode("-", $ativo->data_inicio)))), 'B', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data Fim: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(implode("/", array_reverse(explode("-", $ativo->data_fim)))), 'B', 0, 'L');

            $row_resp = $row_resp + 5;
        }

        //inativos
        $row_resp = $row_resp + 5;
        $pdf->SetY($row_resp);
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(28, 5, utf8_decode("Inativos"), 0, 0, 'L');
        $row_resp = $row_resp + 5;

        foreach ($responsaveis_inativos as $inativo) {
            if ($row_resp >= 260) {
                $row_resp = 35;
                $pdf->AddPage();
            }

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("CPF / Nome: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 5, utf8_decode($inativo->user->cpf . ' - ' . $inativo->user->name), 'T', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Fun????o: "), 'T', 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode($inativo->funcao->descricao), 'T', 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Portaria: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 5, utf8_decode($inativo->portaria), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 5, utf8_decode("Telefone Fixo: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(48, 5, utf8_decode($inativo->telefone_fixo), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(25, 5, utf8_decode("Telefone Celular: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode($inativo->telefone_celular), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Unidade: "), 0, 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(($inativo->instalacao_id) ? $inativo->instalacao->nome : ''), 0, 0, 'L');

            $row_resp = $row_resp + 5;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data In??cio: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 5, utf8_decode(implode("/", array_reverse(explode("-", $inativo->data_inicio)))), 'B', 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 5, utf8_decode("Data Fim: "), "B", 0, 'L');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 5, utf8_decode(implode("/", array_reverse(explode("-", $inativo->data_fim)))), 'B', 0, 'L');

            $row_resp = $row_resp + 5;
        }

        //execu??ao orcamentaria e financeira - empenhos
        $pdf->AddPage();
        $pdf->SetY("28");
        $pdf->SetFont('Arial', 'BIU', 10);
        $pdf->Cell(0, 5, utf8_decode("Execu????o Or??ament??ria e Financeira") . ' - Contrato num.: ' . utf8_decode($contrato->numero) . ' - UG: ' . utf8_decode($contrato->unidade->codigo . " - " . $contrato->unidade->nomeresumido), 0, 0, 'C');

        $pdf->SetY("35");
        $pdf->SetFont('Arial', 'BU', 10);
        $pdf->Cell(28, 5, utf8_decode("Empenhos"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 5, utf8_decode("R$"), 0, 0, 'R');

        $empenhos = $contrato->empenhos()->get();

        $pdf->SetY(40);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(21, 5, utf8_decode("N??mero"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Empenhado"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("A Liquidar"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Liquidado"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("Pago"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP Inscr."), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP A Liq."), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP Liquidado"), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode("RP Pago"), 1, 0, 'C');

        $t_empenhado = 0;
        $t_aliquidar = 0;
        $t_liquidado = 0;
        $t_pago = 0;
        $t_rpinscrito = 0;
        $t_rpaliquidar = 0;
        $t_rpliquidado = 0;
        $t_rppago = 0;

        $row_resp = 40 + 5;

        foreach ($empenhos as $empenho) {
            if ($row_resp >= 260) {
                $row_resp = 35;
                $pdf->AddPage();
                $pdf->SetY($row_resp);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(21, 5, utf8_decode("N??mero"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Empenhado"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("A Liquidar"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Liquidado"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("Pago"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP Inscr."), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP A Liq."), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP Liquidado"), 1, 0, 'C');
                $pdf->Cell(21, 5, utf8_decode("RP Pago"), 1, 0, 'C');
                $row_resp += 5;
            }

            $t_empenhado += $empenho->empenho->empenhado;
            $t_aliquidar += $empenho->empenho->aliquidar;
            $t_liquidado += $empenho->empenho->liquidado;
            $t_pago += $empenho->empenho->pago;
            $t_rpinscrito += $empenho->empenho->rpinscrito;
            $t_rpaliquidar += $empenho->empenho->rpaliquidar;
            $t_rpliquidado += $empenho->empenho->rpliquidado;
            $t_rppago += $empenho->empenho->rppago;

            $pdf->SetY($row_resp);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(21, 5, utf8_decode($empenho->empenho->numero), 1, 0, 'L');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->empenhado, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->aliquidar, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->liquidado, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->pago, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rpinscrito, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rpaliquidar, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rpliquidado, 2, ',', ".")), 1, 0, 'R');
            $pdf->Cell(21, 5, utf8_decode(number_format($empenho->empenho->rppago, 2, ',', ".")), 1, 0, 'R');

            $row_resp += 5;
        }

        $pdf->SetY($row_resp);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(21, 5, utf8_decode("Total"), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_empenhado, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_aliquidar, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_liquidado, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_pago, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rpinscrito, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rpaliquidar, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rpliquidado, 2, ',', ".")), 1, 0, 'R');
        $pdf->Cell(21, 5, utf8_decode(number_format($t_rppago, 2, ',', ".")), 1, 0, 'R');

        $nome_arquivo = str_replace('/', '', $contrato->numero) . ' - ' . str_replace(' ', '_', $contrato->fornecedor->nome) . '.pdf';

        $pdf->Output('D', $nome_arquivo);
    }

    private function calculaLinhasMultiCell($qtdcaracter, $ultimamedida)
    {
        $div = $qtdcaracter / 100;
        $ndiv = explode('.', $div);
        $linha = $ndiv[0] + 2;
        $tam = $linha * 5;
        $tamanho = $ultimamedida + $tam;

        return $tamanho;
    }

    public function notificaUsers()
    {
        $alerta_mensal = new AlertaContratoJob();

        return redirect()->back();
    }

    protected function adicionaCampos()
    {
        $request = Request();

        $this->tab = 'Dados do contrato';

        $this->adicionaCampoFornecedor($request);
        $this->adicionarMinutasDeEmpenho();
        $this->adicionaCampoDataAssinatura();
        $this->adicionaCampoDataPublicacao();
        $this->adicionaCampoObjeto();
        $this->adicionaCampoInformacoesComplementares();
        $this->adicionaCampoUnidadeCompra();
        $this->adicionaCampoModalidades();
        $this->adicionaCampoAmparoLegal();
        $this->adicionaCampoNumeroLicitacao();

        $this->tab = 'Caracter??sticas do contrato';

        $this->adicionaCampoReceitaDespesa();
        $this->adicionaCampoTipo();
        $this->adicionaCampoSubTipo();
        $this->adicionaCampoCategoria();
        $this->adicionaCampoSubCategoria();
        $this->adicionaCampoNumeroContrato();
        $this->adicionaCampoProcesso();
        $this->adicionaCampoUnidadeGestoraOrigem();
        $this->adicionaCampoUnidadeGestoraAtual();
        $this->adicionaCampoUnidadeRequisitante();
        $this->adicionaCampoSituacao();

        $this->tab = 'Itens do contrato';

        $this->adicionaCampoItensContrato();
        $this->adicionaCampoRecuperaGridItens();

        $this->tab = 'Vig??ncia / Valores';

        $this->adicionaCampoDataVigenciaInicio();
        $this->adicionaCampoDataVigenciaTermino();
        $this->adicionaCampoValorGlobal();
        $this->adicionaCampoNumeroParcelas();
        $this->adicionaCampoValorParcela();
    }

    protected function adicionaColunas()
    {
        $this->adicionaColunaReceitaDespesa();
        $this->adicionaColunaNumeroInstrumento();
        $this->adicionaColunaUnidadeOrigem();
        $this->adicionaColunaUnidadeGestora();
        $this->adicionaColunaUnidadeRequisitante();
        $this->adicionaColunaTipo();
        $this->adicionaColunaSubTipo();
        $this->adicionaColunaCategoria();
        $this->adicionaColunaSubCategoria();
        $this->adicionaColunaFornecedor();
        $this->adicionaColunaProcesso();
        $this->adicionaColunaObjeto();
        $this->adicionaColunaInformacoesComplementares();
        $this->adicionaColunaVigenciaInicio();
        $this->adicionaColunaVigenciaTermino();
        $this->adicionaColunaValorGlobal();
        $this->adicionaColunaNumeroParcelas();
        $this->adicionaColunaValorParcela();
        $this->adicionaColunaValorAcumulado();
        $this->adicionaColunaTotalDespesasAcessorias();
        $this->adicionaColunaSituacao();
        $this->adicionaColunaCriadoEm();
        $this->adicionaColunaAtualizadoEm();
    }

    protected function aplicaFiltros()
    {
        // TODO: Melhor consulta do filtro de fornecedores, para n??o buscar a base inteira, mas sim apenas
        //       os fornecedores dos contratos da unidade ativa!
        // $this->aplicaFiltroFornecedor();
        $this->aplicaFiltroReceitaDespesa();
        $this->aplicaFiltroTipo();
        $this->aplicaFiltroCategoria();
        $this->aplicaFiltroDataVigenciaInicio();
        $this->aplicaFiltroDataVigenciaTermino();
        $this->aplicaFiltroValorGlobal();
        $this->aplicaFiltroValorParcela();
        $this->aplicaFiltroSituacao();
    }

    protected function adicionaCampoFornecedor($request)
    {
        $this->crud->addField([
            'label' => "Fornecedor",
            'type' => "select2_from_ajax",
            'name' => 'fornecedor_id',
            'entity' => 'fornecedor',
            'attribute' => "cpf_cnpj_idgener",
            'attribute2' => "nome",
            'process_results_template' => 'gescon.process_results_fornecedor',
            'model' => "App\Models\Fornecedor",
            'data_source' => url("api/fornecedor"),
            'placeholder' => "Selecione o fornecedor",
            'minimum_input_length' => 2,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoRecuperaGridItens()
    {
        $this->crud->addField([
            'label' => "adicionaCampoRecuperaGridItens",
            'type' => "hidden",
            'name' => 'adicionaCampoRecuperaGridItens',
            'default' => "{{old('name')}}",
            'tab' => $this->tab
        ]);
    }


    protected function adicionarMinutasDeEmpenho()
    {
        $this->crud->addField([
            'label' => 'Minutas de Empenho',
            'name' => 'minutasempenho',
            'placeholder' => 'Selecione minutas de empenho',
            'type' => 'select2_from_ajax_multiple_alias',
            'entity' => 'minutaempenho',
            'attribute' => 'nome_minuta_empenho',
            'model' => 'App\Models\MinutaEmpenho',
            'data_source' => url('api/minutaempenhoparacontrato'),
            'dependencies' => ['fornecedor_id'],
            'pivot' => true,
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'title' => '{uasg} {modalidade} {numeroAno} - N?? do(s) Empenho(s) - Data de Emiss??o'
            ],
            'tab' => $this->tab,
        ]);
    }

    protected function adicionaCampoDataAssinatura()
    {
        $this->crud->addField([
            'name' => 'data_assinatura',
            'label' => 'Data da Assinatura',
            'type' => 'date',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoDataPublicacao()
    {
        $this->crud->addField([
            'name' => 'data_publicacao',
            'label' => 'Data da Publica????o',
            'type' => 'date',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoObjeto()
    {
        $this->crud->addField([
            'name' => 'objeto',
            'label' => 'Objeto',
            'type' => 'textarea',
            'attributes' => [
                'onkeyup' => "maiuscula(this)"
            ],
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoInformacoesComplementares()
    {
        $this->crud->addField([
            'name' => 'info_complementar',
            'label' => 'Informa????es Complementares',
            'type' => 'textarea',
            'attributes' => [
                'onkeyup' => "maiuscula(this)"
            ],
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoModalidades()
    {
        $modalidades = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Modalidade Licita????o');
        })->where('visivel', true)->orderBy('descricao')->pluck('descricao', 'id')->toArray();

        $this->crud->addField([
            'name' => 'modalidade_id',
            'label' => "Modalidade Licita????o",
            'type' => 'select2_from_array',
            'options' => $modalidades,
            'allows_null' => true,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoNumeroLicitacao()
    {
        $this->crud->addField([
            'name' => 'licitacao_numero',
            'label' => 'N??mero Licita????o',
            'type' => 'numlicitacao',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoAmparoLegal()
    {
        $this->crud->addField([
            'label' => 'Amparo Legal',
            'name' => 'amparoslegais',
            'type' => 'select2_from_ajax_multiple_alias',
            'entity' => 'amparoslegais',
            'placeholder' => 'Selecione o Amparo Legal',
            'minimum_input_length' => 0,
            'data_source' => url('api/amparolegal'),
            'model' => 'App\Models\AmparoLegal',
            'dependencies' => ['modalidade_id'],
            'attribute' => 'campo_api_amparo',
            'pivot' => true,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoReceitaDespesa()
    {
        $this->crud->addField([
            'name' => 'receita_despesa',
            'label' => "Receita / Despesa",
            'type' => 'select_from_array',
            'options' => [
                'D' => 'Despesa',
                'R' => 'Receita',
            ],
            'default' => 'D',
            'allows_null' => false,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoTipo()
    {
        $this->crud->addField([
            'name' => 'tipo_id',
            'label' => "Tipo",
            'type' => 'select2_from_array',
            'options' => $this->retornaTipos(),
            'attributes' => [
                'id' => 'tipo_contrato',
            ],
            'allows_null' => true,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoSubTipo()
    {
        $this->crud->addField([
            'name' => 'subtipo',
            'label' => 'Subtipo',
            'type' => 'textarea',
            'attributes' => [
                'onkeyup' => "maiuscula(this)"
            ],
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoCategoria()
    {
        $this->crud->addField([
            'name' => 'categoria_id',
            'label' => "Categoria",
            'type' => 'select2_from_array',
            'options' => $this->retornaCategorias(),
            'allows_null' => true,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoSubCategoria()
    {
        $this->crud->addField([
            'name' => 'subcategoria_id',
            'label' => "Subcategoria",
            'type' => 'select2_from_ajax_single',
            'model' => 'App\Models\OrgaoSubcategoria',
            'entity' => 'orgaosubcategoria',
            'attribute' => 'descricao',
            'data_source' => url('api/orgaosubcategoria'),
            'placeholder' => 'Selecione...',
            'minimum_input_length' => 0,
            'dependencies' => ['categoria_id'],
            'method' => 'GET',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoNumeroContrato()
    {
        $this->crud->addField([
            'name' => 'numero',
            'label' => 'Contrato',
            'type' => 'numcontrato',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoProcesso()
    {
        $this->crud->addField([
            'name' => 'processo',
            'label' => 'N??mero Processo',
            'type' => 'numprocesso',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoItensContrato()
    {
        $idMaterial = $this->retornaIdCodigoItem('Tipo CATMAT e CATSER', 'Material');
        $idServico = $this->retornaIdCodigoItem('Tipo CATMAT e CATSER', 'Servi??o');

        $this->crud->addField([
            'name' => 'itens',
            'type' => 'itens_contrato_list',
            'label' => 'Teste',
            'tab' => $this->tab,
            'material' => $idMaterial,
            'servico' => $idServico,
        ]);
    }

    protected function adicionaCampoUnidadeGestoraOrigem()
    {
        $this->crud->addField([
            'label' => "Unidade Gestora Origem do Contrato",
            'type' => "select2_from_ajax_single",
            'name' => 'unidadeorigem_id',
            'entity' => 'unidadeorigem',
            'attribute' => "codigo",
            'attribute2' => "nomeresumido",
            'process_results_template' => 'gescon.process_results_unidade',
            'model' => "App\Models\Unidade",
            'data_source' => url("api/unidade"),
            'placeholder' => "Selecione a Unidade",
            'minimum_input_length' => 2,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoUnidadeCompra()
    {
        $this->crud->addField([
            'label' => "Unidade Compra",
            'type' => "select2_from_ajax_single",
            'name' => 'unidadecompra_id',
            'entity' => 'unidadecompra',
            'attribute' => "codigo",
            'attribute2' => "nomeresumido",
            'process_results_template' => 'gescon.process_results_unidade',
            'model' => "App\Models\Unidade",
            'data_source' => url("api/unidade"),
            'placeholder' => "Selecione a Unidade",
            'minimum_input_length' => 2,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoUnidadeGestoraAtual()
    {
        $unidade = [session()->get('user_ug_id') => session()->get('user_ug')];

        $this->crud->addField([
            'name' => 'unidade_id',
            'label' => "Unidade Gestora Atual",
            'type' => 'select2_from_array',
            'options' => $unidade,
            'allows_null' => false,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoUnidadeRequisitante()
    {
        $this->crud->addField([
            'name' => 'unidades_requisitantes',
            'label' => 'Unidades Requisitantes',
            'type' => 'text',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoSituacao()
    {
        $this->crud->addField([
            'name' => 'situacao',
            'label' => "Situa????o",
            'type' => 'select_from_array',
            'options' => [1 => 'Ativo', 0 => 'Inativo'],
            'allows_null' => false,
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoDataVigenciaInicio()
    {
        $this->crud->addField([
            'name' => 'vigencia_inicio',
            'label' => 'Data de in??cio da vig??ncia',
            'type' => 'date',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoDataVigenciaTermino()
    {
        $this->crud->addField([
            'name' => 'vigencia_fim',
            'label' => 'Data do t??rmino da vig??ncia',
            'type' => 'date',
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoValorGlobal()
    {
        $this->crud->addField([
            'name' => 'valor_global',
            'label' => 'Valor Global',
            'type' => 'number',
            'attributes' => [
                "step" => "0.01",
                'id' => 'valor_global',
                'step' => '0.0001',
                'readOnly' => 'readOnly',
            ],
            'prefix' => "R$",
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoNumeroParcelas()
    {
        $this->crud->addField([
            'name' => 'num_parcelas',
            'label' => 'N??m. Parcelas',
            'type' => 'number',
            'default' => '1',
            'attributes' => [
                "step" => "any",
                "min" => '1',
            ],
            'tab' => $this->tab
        ]);
    }

    protected function adicionaCampoValorParcela()
    {
        $this->crud->addField([
            'name' => 'valor_parcela',
            'label' => 'Valor Parcela',
            'type' => 'number',
            'attributes' => [
                "step" => "0.01",
                'id' => 'valor_parcela',
                'step' => '0.0001',
                'readOnly' => 'readOnly',
            ],
            'prefix' => "R$",
            'tab' => $this->tab
        ]);
    }

    protected function adicionaColunaReceitaDespesa()
    {
        $this->crud->addColumn([
            'name' => 'getReceitaDespesa',
            'label' => 'Receita / Despesa',
            'type' => 'model_function',
            'function_name' => 'getReceitaDespesa',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaNumeroInstrumento()
    {
        $this->crud->addColumn([
            'name' => 'numero',
            'label' => 'N??mero do instrumento',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaUnidadeOrigem()
    {
        $this->crud->addColumn([
            'name' => 'getUnidadeOrigem',
            'label' => 'Unidade Gestora Origem do Contrato',
            'type' => 'model_function',
            'function_name' => 'getUnidadeOrigem',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaUnidadeCompra()
    {
        $this->crud->addColumn([
            'name' => 'getUnidadeCompra',
            'label' => 'Unidade da Compra',
            'type' => 'model_function',
            'function_name' => 'getUnidadeCompra',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaUnidadeGestora()
    {
        $this->crud->addColumn([
            'name' => 'getUnidade',
            'label' => 'Unidade Gestora Atual',
            'type' => 'model_function',
            'function_name' => 'getUnidade',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaUnidadeRequisitante()
    {
        $this->crud->addColumn([
            'name' => 'unidades_requisitantes',
            'label' => 'Unidades Requisitantes',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaTipo()
    {
        $this->crud->addColumn([
            'name' => 'getTipo',
            'label' => 'Tipo',
            'type' => 'model_function',
            'function_name' => 'getTipo',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaSubTipo()
    {
        $this->crud->addColumn([
            'name' => 'subtipo',
            'label' => 'Subtipo',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaCategoria()
    {
        $this->crud->addColumn([
            'name' => 'getCategoria',
            'label' => 'Categoria',
            'type' => 'model_function',
            'function_name' => 'getCategoria',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaSubCategoria()
    {
        $this->crud->addColumn([
            'name' => 'getSubCategoria',
            'label' => 'Subcategoria',
            'type' => 'model_function',
            'function_name' => 'getSubCategoria',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaFornecedor()
    {
        $this->crud->addColumn([
            'name' => 'getFornecedor',
            'label' => 'Fornecedor',
            'type' => 'model_function',
            'function_name' => 'getFornecedor',
            'orderable' => true,
            'limit' => 50,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'searchLogic' => function (Builder $query, $column, $searchTerm) {
                $query->orWhere('fornecedores.cpf_cnpj_idgener', 'like', "%" . strtoupper($searchTerm) . "%");
                $query->orWhere('fornecedores.nome', 'like', "%" . strtoupper($searchTerm) . "%");
            },
        ]);
    }

    protected function adicionaColunaProcesso()
    {
        $this->crud->addColumn([
            'name' => 'processo',
            'label' => 'Processo',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaObjeto()
    {
        $this->crud->addColumn([
            'name' => 'objeto',
            'label' => 'Objeto',
            'type' => 'text',
            'limit' => 1000,
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaInformacoesComplementares()
    {
        $this->crud->addColumn([
            'name' => 'info_complementar',
            'label' => 'Informa????es Complementares',
            'type' => 'text',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaVigenciaInicio()
    {
        $this->crud->addColumn([
            'name' => 'vigencia_inicio',
            'label' => 'Vig. In??cio',
            'type' => 'date',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaVigenciaTermino()
    {
        $this->crud->addColumn([
            'name' => 'vigencia_fim',
            'label' => 'Vig. Fim',
            'type' => 'date',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaValorGlobal()
    {
        $this->crud->addColumn([
            'name' => 'formatVlrGlobal',
            'label' => 'Valor Global',
            'type' => 'model_function',
            'function_name' => 'formatVlrGlobal',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaNumeroParcelas()
    {
        $this->crud->addColumn([
            'name' => 'num_parcelas',
            'label' => 'N??m. Parcelas',
            'type' => 'number',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaValorParcela()
    {
        $this->crud->addColumn([
            'name' => 'formatVlrParcela',
            'label' => 'Valor Parcela',
            'type' => 'model_function',
            'function_name' => 'formatVlrParcela',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaValorAcumulado()
    {
        $this->crud->addColumn([
            'name' => 'formatVlrAcumulado',
            'label' => 'Valor Acumulado',
            'type' => 'model_function',
            'function_name' => 'formatVlrAcumulado',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaTotalDespesasAcessorias()
    {
        $this->crud->addColumn([
            'name' => 'formatTotalDespesasAcessorias',
            'label' => 'Total Despesas Acess??rias',
            'type' => 'model_function',
            'function_name' => 'formatTotalDespesasAcessorias',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true
        ]);
    }

    protected function adicionaColunaSituacao()
    {
        $this->crud->addColumn([
            'name' => 'situacao',
            'label' => 'Situa????o',
            'type' => 'boolean',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
            'options' => [0 => 'Inativo', 1 => 'Ativo']
        ]);
    }

    protected function adicionaColunaCriadoEm()
    {
        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => 'Criado em',
            'type' => 'datetime',
            'orderable' => true,
            'visibleInTable' => false,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    protected function adicionaColunaAtualizadoEm()
    {
        $this->crud->addColumn([
            'name' => 'updated_at',
            'label' => 'Atualizado em',
            'type' => 'datetime',
            'orderable' => true,
            'visibleInTable' => true,
            'visibleInModal' => true,
            'visibleInExport' => true,
            'visibleInShow' => true,
        ]);
    }

    protected function aplicaFiltroFornecedor()
    {
        $this->crud->addFilter(
            [
                'name' => 'fornecedor',
                'type' => 'select2_multiple',
                'label' => 'Fornecedor'
            ],
            $this->retornaFornecedores(),
            function ($value) {
                $this->crud->addClause(
                    'whereIn',
                    'fornecedores.cpf_cnpj_idgener',
                    json_decode($value)
                );
            }
        );
    }

    protected function aplicaFiltroReceitaDespesa()
    {
        $this->crud->addFilter(
            [
                'name' => 'receita_despesa',
                'type' => 'select2_multiple',
                'label' => 'Receita / Despesa'
            ],
            [
                'R' => 'Receita',
                'D' => 'Despesa',
            ],
            function ($value) {
                $this->crud->addClause(
                    'whereIn',
                    'contratos.receita_despesa',
                    json_decode($value)
                );
            }
        );
    }

    protected function aplicaFiltroTipo()
    {
        $this->crud->addFilter(
            [
                'name' => 'tipo_contrato',
                'type' => 'select2_multiple',
                'label' => 'Tipo'
            ],
            $this->retornaTipos(),
            function ($value) {
                $this->crud->addClause(
                    'whereIn',
                    'contratos.tipo_id',
                    json_decode($value)
                );
            }
        );
    }

    protected function aplicaFiltroCategoria()
    {
        $this->crud->addFilter(
            [
                'name' => 'categorias',
                'type' => 'select2_multiple',
                'label' => 'Categorias'
            ],
            $this->retornaCategorias(),
            function ($values) {
                $this->crud->addClause(
                    'whereIn',
                    'contratos.categoria_id',
                    json_decode($values)
                );
            }
        );
    }

    protected function aplicaFiltroDataVigenciaInicio()
    {
        $this->crud->addFilter(
            [
                'type' => 'date_range',
                'name' => 'vigencia_inicio',
                'label' => 'Vig??ncia Inicio'
            ],
            false,
            function ($value) {
                $dates = json_decode($value);

                $this->crud->addClause('where', 'contratos.vigencia_inicio', '>=', $dates->from);
                $this->crud->addClause('where', 'contratos.vigencia_inicio', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    protected function aplicaFiltroDataVigenciaTermino()
    {
        $this->crud->addFilter(
            [
                'type' => 'date_range',
                'name' => 'vigencia_fim',
                'label' => 'Vig??ncia Fim'
            ],
            false,
            function ($value) {
                $dates = json_decode($value);

                $this->crud->addClause('where', 'contratos.vigencia_fim', '>=', $dates->from);
                $this->crud->addClause('where', 'contratos.vigencia_fim', '<=', $dates->to . ' 23:59:59');
            }
        );
    }

    protected function aplicaFiltroValorGlobal()
    {
        $this->crud->addFilter(
            [
                'name' => 'valor_global',
                'type' => 'range',
                'label' => 'Valor Global',
                'label_from' => 'Vlr M??nimo',
                'label_to' => 'Vlr M??ximo'
            ],
            false,
            function ($value) {
                $range = json_decode($value);

                if ($range->from) {
                    $this->crud->addClause('where', 'contratos.valor_global', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'contratos.valor_global', '<=', (float)$range->to);
                }
            }
        );
    }

    protected function aplicaFiltroValorParcela()
    {
        $this->crud->addFilter(
            [
                'name' => 'valor_parcela',
                'type' => 'range',
                'label' => 'Valor Parcela',
                'label_from' => 'Vlr M??nimo',
                'label_to' => 'Vlr M??ximo'
            ],
            false,
            function ($value) {
                $range = json_decode($value);

                if ($range->from) {
                    $this->crud->addClause('where', 'contratos.valor_parcela', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'contratos.valor_parcela', '<=', (float)$range->to);
                }
            }
        );
    }

    protected function aplicaFiltroSituacao()
    {
        $this->crud->addFilter([
            'name' => 'situacao',
            'type' => 'select2_multiple',
            'label' => 'Situa????o'
        ], [
            '1' => 'Ativo',
            '0' => 'Inativo',
        ], function ($value) {
            $this->crud->addClause(
                'whereIn',
                'contratos.situacao',
                json_decode($value)
            );
        });
    }

    private function retornaFornecedores()
    {
        return Fornecedor::select(
            DB::raw("CONCAT(cpf_cnpj_idgener,' - ',nome) AS nome"),
            'cpf_cnpj_idgener'
        )
            ->whereHas(
                'contratos',
                function ($u) {
                    $u->where('situacao', true);
                    $u->where('unidade_id', session('user_ug_id'));
                }
            )

            ->pluck('nome', 'cpf_cnpj_idgener')
            ->toArray();
    }

    private function retornaTipos()
    {
        return Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Tipo de Contrato');
        })
            ->where('descricao', '<>', 'Termo Aditivo')
            ->where('descricao', '<>', 'Termo de Apostilamento')
            ->where('descricao', '<>', 'Termo de Rescis??o')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();
    }

    private function retornaCategorias()
    {
        return Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Categoria Contrato');
        })
            ->where('descricao', '<>', 'A definir')
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->toArray();
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $contratos = Contrato::where('id', $id)->has('minutasempenho')->get();
        $minutas = MinutaEmpenho::where('contrato_id', $id)->get();

        //SE N??O HOUVER CONTRATO VINCULADO A MINUTA, DEIXA DELETAR
        if ($contratos->isEmpty() && $minutas->isEmpty()) {
            return $this->crud->delete($id);
        }

        return 'delete_confirmation_not_deleted_message_vinculacao';
    }
}
