<?php

namespace App\Http\Controllers\Api;

use App\Models\AmparoLegal;
use App\Models\Codigoitem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TermoAditivoController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');


        $options = Codigoitem::select('codigoitens.id','codigoitens.descricao')
                            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
                            ->where('codigos.descricao', '=', 'Tipo Qualificacao Contrato');


        if ($search_term) {
            return $options->where('codigoitens.descricao', 'ilike', '%' . strtoupper($search_term) . '%')
                ->orderBy('codigoitens.descricao')
                ->paginate(10);
        }

        return $options->paginate(10);
    }

    public function show($id)
    {
        return Codigoitem::find($id);
    }
}
