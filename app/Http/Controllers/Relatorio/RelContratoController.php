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

    public function index(Request $request)
    {
        $this->data['title'] = "Lista Contratos";

        $form = \FormBuilder::create(FiltroRelatorioContratosForm::class, [
            'method' => 'GET',
            'model' => ($request->input())? $request->input():'',
            'url' => route('relatorio.listacontrato'),
        ]);

        if($request->input()){
            $data = $form->getFieldValues();
        }

        $contratos = Contrato::all();

        //datatables
        if ($request->ajax()) {
            $grid = DataTables::of($contratos);
//            $grid->editColumn('fornecedor', '{!! $fornecedor_id !!}');
            return $grid->make(true);
        }

        $html = $this->retornaGrid();

        return view('backpack::mod.relatorios.relatoriolistacontrato',
            [
                'data' => $this->data,
                'form' => $form,
                'dataTable' => $html
            ]);
    }

    public function filter()
    {
        $this->data['title'] = "Lista Contratos";

        $form = \FormBuilder::create(FiltroRelatorioContratosForm::class, [
            'method' => 'POST',
            'url' => route('relatorio.listacontrato'),
        ]);

        return view('backpack::mod.relatorios.filtrolistacontrato',
            [
                'data' => $this->data,
                'form' => $form,
            ]);
    }

    private function retornaGrid()
    {
        $html = $this->htmlBuilder;

        $html->addColumn([
            'data' => 'numero',
            'name' => 'numero',
            'title' => 'Número',
        ]);
        $html->addColumn([
            'data' => 'fornecedor_id',
            'name' => 'fornecedor_id',
            'title' => 'Fornecedor',
//            'class' => 'text-right'
        ]);


        $html->parameters([
            'processing' => true,
            'searching' => false,
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
