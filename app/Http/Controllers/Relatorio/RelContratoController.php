<?php

namespace App\Http\Controllers\Relatorio;

use App\Forms\FiltroRelatorioContratosForm;
use App\Models\Contrato;
use App\Models\Empenho;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class RelContratoController extends Controller
{
    protected $data = []; // the information we send to the view

    public function __construct(Builder $htmlBuilder)
    {
        $this->htmlBuilder = $htmlBuilder;
        $this->middleware(backpack_middleware());
    }

    public function listaTodosContratos(Request $request)
    {
        $this->data['title'] = "Lista Todos Contratos";
        $this->data['download_route'] = "listatodoscontratos.download";
        $this->data['filtro_route'] = "filtro.listatodoscontratos";

        $filtro = null;

        if ($request->input()) {
            $filtro = $request->input();
        }

        $model = new Contrato();
        $dados = $model->buscaListaTodosContratos($filtro);


        //datatables
        if ($request->ajax()) {
            $grid = DataTables::of($dados);
            $grid->editColumn('data_assinatura', '{!! implode(\'/\',array_reverse(explode(\'-\',$data_assinatura))) !!}');
            $grid->editColumn('data_publicacao', '{!! implode(\'/\',array_reverse(explode(\'-\',$data_publicacao))) !!}');
            $grid->editColumn('vigencia_inicio', '{!! implode(\'/\',array_reverse(explode(\'-\',$vigencia_inicio))) !!}');
            $grid->editColumn('vigencia_fim', '{!! implode(\'/\',array_reverse(explode(\'-\',$vigencia_fim))) !!}');
            $grid->editColumn('valor_inicial', '{!! number_format($valor_inicial,2,\',\', \'.\') !!}');
            $grid->editColumn('valor_global', '{!! number_format($valor_global,2,\',\', \'.\') !!}');
            $grid->editColumn('valor_parcela', '{!! number_format($valor_parcela,2,\',\', \'.\') !!}');
            $grid->editColumn('valor_acumulado', '{!! number_format($valor_acumulado,2,\',\', \'.\') !!}');
            return $grid->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.relatorios.relatorio',
            [
                'data' => $this->data,
                'filtro' => $filtro,
                'dataTable' => $html
            ]);
    }

    public function filtroListaTodosContratos(Request $request)
    {
        $this->data['title'] = "Filtro - Lista Todos Contratos";
        $this->data['relatorio_route'] = "relatorio.listatodoscontratos";

        $form = \FormBuilder::create(FiltroRelatorioContratosForm::class, [
            'method' => 'GET',
            'model' => ($request->input()) ? $request->input() : '',
            'url' => route('relatorio.listatodoscontratos'),
        ]);

        if ($request->input()) {
            $data = $form->getFieldValues();
        }

        return view('backpack::mod.relatorios.filtro',
            [
                'data' => $this->data,
                'form' => $form,
            ]);
    }

    private function retornaGrid()
    {
        $html = $this->htmlBuilder;


        $html->addColumn([
            'data' => 'orgao',
            'name' => 'orgao',
            'title' => 'Órgão',
//            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'unidade',
            'name' => 'unidade',
            'title' => 'Unidade',
//            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'receita_despesa',
            'name' => 'receita_despesa',
            'title' => 'Receita / Despesa',
        ]);
        $html->addColumn([
            'data' => 'tipo',
            'name' => 'tipo',
            'title' => 'Tipo',
        ]);
        $html->addColumn([
            'data' => 'categoria',
            'name' => 'categoria',
            'title' => 'Categoria',
        ]);
        $html->addColumn([
            'data' => 'subcategoria',
            'name' => 'subcategoria',
            'title' => 'Subcategoria',
        ]);
        $html->addColumn([
            'data' => 'unidades_requisitantes',
            'name' => 'unidades_requisitantes',
            'title' => 'Unid. Requisitantes',
        ]);
        $html->addColumn([
            'data' => 'numero',
            'name' => 'numero',
            'title' => 'Número',
        ]);
        $html->addColumn([
            'data' => 'fornecedor_codigo',
            'name' => 'fornecedor_codigo',
            'title' => 'CNPJ/CPF/UG/Id Genérico',
//            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'fornecedor_nome',
            'name' => 'fornecedor_nome',
            'title' => 'Nome',
//            'class' => 'text-right'
        ]);
        $html->addColumn([
            'data' => 'processo',
            'name' => 'processo',
            'title' => 'Processo',
        ]);
        $html->addColumn([
            'data' => 'objeto',
            'name' => 'objeto',
            'title' => 'Objeto',
        ]);
        $html->addColumn([
            'data' => 'info_complementar',
            'name' => 'info_complementar',
            'title' => 'Inf. Complementar',
        ]);
        $html->addColumn([
            'data' => 'modalidade',
            'name' => 'modalidade',
            'title' => 'Mod. Licitação',
        ]);
        $html->addColumn([
            'data' => 'licitacao_numero',
            'name' => 'licitacao_numero',
            'title' => 'Núm. Licitação',
        ]);
        $html->addColumn([
            'data' => 'data_assinatura',
            'name' => 'data_assinatura',
            'title' => 'Dt. Assinatura',
        ]);
        $html->addColumn([
            'data' => 'data_publicacao',
            'name' => 'data_publicacao',
            'title' => 'Dt. Publicação',
        ]);
        $html->addColumn([
            'data' => 'vigencia_inicio',
            'name' => 'vigencia_inicio',
            'title' => 'Vig. Início',
        ]);
        $html->addColumn([
            'data' => 'vigencia_fim',
            'name' => 'vigencia_fim',
            'title' => 'Vig. Fim',
        ]);
        $html->addColumn([
            'data' => 'valor_inicial',
            'name' => 'valor_inicial',
            'title' => 'Valor Inicial',
        ]);
        $html->addColumn([
            'data' => 'valor_global',
            'name' => 'valor_global',
            'title' => 'Valor Global',
        ]);
        $html->addColumn([
            'data' => 'num_parcelas',
            'name' => 'num_parcelas',
            'title' => 'Núm. Parcelas',
        ]);
        $html->addColumn([
            'data' => 'valor_parcela',
            'name' => 'valor_parcela',
            'title' => 'Valor Parcela',
        ]);
        $html->addColumn([
            'data' => 'valor_acumulado',
            'name' => 'valor_acumulado',
            'title' => 'Valor Acumulado',
        ]);
        $html->addColumn([
            'data' => 'situacao',
            'name' => 'situacao',
            'title' => 'Situação',
        ]);


        $html->parameters([
            'processing' => true,
            'searching' => true,
            'serverSide' => true,
            'responsive' => true,
            'pageLength' => 25,
            'fixedHeader' => [
                'header' => false,
                'footer' => true
            ],
            'info' => true,
            'autoWidth' => false,
            'bAutoWidth' => false,
            'paging' => true,
            'lengthChange' => true,
            'language' => [
                'url' => asset('/json/pt_br.json')
            ],

        ]);

        return $html;
    }

}
