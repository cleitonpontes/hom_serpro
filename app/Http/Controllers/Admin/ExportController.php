<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Painel\OrcamentarioController;
use App\Models\Apropriacao;
//use App\Models\Permission;
//use App\Models\Role;
//use App\Models\Unidade;
use App\Models\Contrato;
use App\Models\Empenho;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
//    public function downloadUnidade(Request $request, $type)
//    {
//        $data = Unidade::get()->toArray();
//        return Excel::create('unidades', function($excel) use ($data) {
//            $excel->sheet('lista', function($sheet) use ($data)
//            {
//                $sheet->fromArray($data);
//            });
//        })->download($type);
//    }
//
//
//    public function exportUnidade(Request $request, $type)
//    {
//        $data = Unidade::get()->toArray();
//        return Excel::create('unidades', function($excel) use ($data) {
//            $excel->sheet('lista', function($sheet) use ($data)
//            {
//                $sheet->fromArray($data);
//            });
//        })->export($type);
//    }
//
//
//    public function downloadpermissao(Request $request, $type)
//    {
//        $data = Permission::get()->toArray();
//        return Excel::create('permissao', function($excel) use ($data) {
//            $excel->sheet('lista', function($sheet) use ($data)
//            {
//                $sheet->fromArray($data);
//            });
//        })->download($type);
//    }
//
//    public function exportpermissao(Request $request, $type)
//    {
//        $data = Permission::get()->toArray();
//        return Excel::create('permissao', function($excel) use ($data) {
//            $excel->sheet('lista', function($sheet) use ($data)
//            {
//                $sheet->fromArray($data);
//            });
//        })->export($type);
//    }
//
//
//    public function downloadgrupo(Request $request, $type)
//    {
//        $data = Role::get()->toArray();
//        return Excel::create('grupo', function($excel) use ($data) {
//            $excel->sheet('lista', function($sheet) use ($data)
//            {
//                $sheet->fromArray($data);
//            });
//        })->download($type);
//    }
//
//    public function exportgrupo(Request $request, $type)
//    {
//        $data = Role::get()->toArray();
//        return Excel::create('grupo', function($excel) use ($data) {
//            $excel->sheet('lista', function($sheet) use ($data)
//            {
//                $sheet->fromArray($data);
//            });
//        })->export($type);
//    }

    public function downloadListaTodosContratos(Request $request, $type)
    {
        $filtro = null;

        if ($request->input()) {
            $filtro = $request->input();
        }

        $modelo = new Contrato();
        $dados = $modelo->buscaListaTodosContratos($filtro);

        $dados = $dados->toArray();

        $data = [];
        foreach ($dados as $dado){
            $valores_replace=[
                'data_assinatura' => implode('/',array_reverse(explode('-',$dado['data_assinatura']))),
                'data_publicacao' => implode('/',array_reverse(explode('-',$dado['data_publicacao']))),
                'vigencia_inicio' => implode('/',array_reverse(explode('-',$dado['vigencia_inicio']))),
                'vigencia_fim' => implode('/',array_reverse(explode('-',$dado['vigencia_fim']))),
                'valor_inicial' => number_format($dado['valor_inicial'],2,',','.'),
                'valor_global' => number_format($dado['valor_global'],2,',','.'),
                'num_parcelas' => number_format($dado['num_parcelas'],0),
                'valor_parcela' => number_format($dado['valor_parcela'],2,',','.'),
                'valor_acumulado' => number_format($dado['valor_acumulado'],2,',','.'),
                'situacao' => $dado['situacao'],
            ];


            $data[]=array_replace($dado,$valores_replace);

        }

        return Excel::create('todos_contratos_'. date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('lista', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export($type);
    }

    public function downloadListaContratosOrgao(Request $request, $type)
    {
        $filtro = null;

        if ($request->input()) {
            $filtro = $request->input();
        }

        $modelo = new Contrato();
        $dados = $modelo->buscaListaContratosOrgao($filtro);

        $dados = $dados->toArray();

        $data = [];
        foreach ($dados as $dado){
            $valores_replace=[
                'data_assinatura' => implode('/',array_reverse(explode('-',$dado['data_assinatura']))),
                'data_publicacao' => implode('/',array_reverse(explode('-',$dado['data_publicacao']))),
                'vigencia_inicio' => implode('/',array_reverse(explode('-',$dado['vigencia_inicio']))),
                'vigencia_fim' => implode('/',array_reverse(explode('-',$dado['vigencia_fim']))),
                'valor_inicial' => number_format($dado['valor_inicial'],2,',','.'),
                'valor_global' => number_format($dado['valor_global'],2,',','.'),
                'num_parcelas' => number_format($dado['num_parcelas'],0),
                'valor_parcela' => number_format($dado['valor_parcela'],2,',','.'),
                'valor_acumulado' => number_format($dado['valor_acumulado'],2,',','.'),
                'situacao' => $dado['situacao'],
            ];


            $data[]=array_replace($dado,$valores_replace);

        }

        return Excel::create('contratos_orgao_'. date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('lista', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export($type);
    }

    public function downloadListaContratosUg(Request $request, $type)
    {
        $filtro = null;

        if ($request->input()) {
            $filtro = $request->input();
        }

        $modelo = new Contrato();
        $dados = $modelo->buscaListaContratosUg($filtro);

        $dados = $dados->toArray();

        $data = [];
        foreach ($dados as $dado){
            $valores_replace=[
                'data_assinatura' => implode('/',array_reverse(explode('-',$dado['data_assinatura']))),
                'data_publicacao' => implode('/',array_reverse(explode('-',$dado['data_publicacao']))),
                'vigencia_inicio' => implode('/',array_reverse(explode('-',$dado['vigencia_inicio']))),
                'vigencia_fim' => implode('/',array_reverse(explode('-',$dado['vigencia_fim']))),
                'valor_inicial' => number_format($dado['valor_inicial'],2,',','.'),
                'valor_global' => number_format($dado['valor_global'],2,',','.'),
                'num_parcelas' => number_format($dado['num_parcelas'],0),
                'valor_parcela' => number_format($dado['valor_parcela'],2,',','.'),
                'valor_acumulado' => number_format($dado['valor_acumulado'],2,',','.'),
                'situacao' => $dado['situacao'],
            ];


            $data[]=array_replace($dado,$valores_replace);

        }

        return Excel::create('contratos_ug_'. date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('lista', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export($type);
    }

    public function downloadapropriacao(Request $request, $type)
    {
        $modelo = new Apropriacao();
        $apropriacao = $modelo->retornaListagem();

        $data = $apropriacao->toArray();

        return Excel::create('apropriacao_'. date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('lista', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export($type);
    }


    public function downloadExecucaoPorEmpenho(Request $request, $type)
    {

        $modelo = new Empenho();
        $dados = $modelo->retornaDadosEmpenhosGroupUgArray();
        $totais = $modelo->retornaDadosEmpenhosSumArray();

        $totais_linha = [];
        foreach ($totais as $total){
            $totais_linha[] = [
                'nome' => 'Total',
                'empenhado' => $total['empenhado'],
                'aliquidar' => $total['aliquidar'],
                'liquidado' => $total['liquidado'],
                'pago' => $total['pago'],
            ];
        }
        $data = array_merge($dados,$totais_linha);

        return Excel::create('ExecucaoPorEmpenho_'. date('YmdHis'), function ($excel) use ($data) {
            $excel->sheet('lista', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export($type);
    }

}
