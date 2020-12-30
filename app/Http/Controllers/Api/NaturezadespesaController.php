<?php

namespace App\Http\Controllers\Api;

use App\Models\Naturezadespesa;
use App\Models\Planointerno;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NaturezadespesaController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Naturezadespesa::where('codigo', 'ILIKE', '%'.strtoupper($search_term).'%')
                ->orWhere('descricao', 'ILIKE', '%'.strtoupper($search_term).'%')
                ->orderBy('codigo','asc')
                ->paginate(10);
        }
        else
        {
            $results = Naturezadespesa::orderBy('codigo','asc')
                ->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Naturezadespesa::find($id);
    }
}
