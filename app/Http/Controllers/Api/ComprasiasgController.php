<?php

namespace App\Http\Controllers\Api;

use App\Models\Siasgcompra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ComprasiasgController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = Siasgcompra::select(
                DB::raw("CONCAT(unidades.codigosiasg,'-',unidades.nomeresumido) AS unidadecompra"),
                DB::raw("CONCAT(siasgcompras.numero,'/',siasgcompras.ano) AS numerocompra"),
                'siasgcompras.id AS id'
            )
                ->join('unidades', 'siasgcompras.unidade_id', '=', 'unidades.id')
                ->where('unidades.codigosiasg', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('unidades.nomeresumido', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('siasgcompras.numero', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('siasgcompras.ano', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->paginate(10);
        } else {
            $results = Siasgcompra::select(
                DB::raw("CONCAT(unidades.codigosiasg,' - ',unidades.nomeresumido) AS unidadecompra"),
                DB::raw("CONCAT(siasgcompras.numero,' - ',siasgcompras.ano) AS numerocompra"),
                'siasgcompras.id AS id'
            )
                ->join('unidades', 'siasgcompras.unidade_id', '=', 'unidades.id')
                ->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Siasgcompra::select(
            DB::raw("CONCAT(unidades.codigosiasg,' - ',unidades.nomeresumido) AS unidadecompra"),
            DB::raw("CONCAT(siasgcompras.numero,' - ',siasgcompras.ano) AS numerocompra"),
            'siasgcompras.id AS id'
        )
            ->join('unidades', 'siasgcompras.unidade_id', '=', 'unidades.id')
            ->where('siasgcompras.id',$id)
            ->first();
    }
}
