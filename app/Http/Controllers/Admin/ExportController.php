<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apropriacao;
//use App\Models\Permission;
//use App\Models\Role;
//use App\Models\Unidade;
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
    
    public function downloadapropriacao(Request $request, $type)
    {
        $modelo = new Apropriacao();
        $apropriacao = $modelo->retornaListagem();
        
        $data = $apropriacao->toArray();
        
        return Excel::create('apropriacao', function ($excel) use ($data) {
            $excel->sheet('lista', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export($type);
    }
    
}
