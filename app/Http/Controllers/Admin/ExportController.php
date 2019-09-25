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

        $data = $dados->toArray();

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

        $data = $dados->toArray();

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

        $data = $dados->toArray();

        return Excel::create('contratos_orgao_'. date('YmdHis'), function ($excel) use ($data) {
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
