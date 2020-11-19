<?php

namespace App\Http\Controllers\Api;

use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class UnidadeController extends Controller
{

    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        // se for passado na consulta o id do orgão será filtrado pelo id e pela serarch_term
        if ($search_term)
        {

            $results = Unidade::where('codigo', 'LIKE', '%'.strtoupper($search_term).'%')
                ->orWhere('nome', 'LIKE', '%'.strtoupper($search_term).'%')
                ->orWhere('nomeresumido', 'LIKE', '%'.strtoupper($search_term).'%')
                ->paginate(10);
        }
        else
        {
            $results = Unidade::paginate(10);
        }

    }

    public function show($id)
    {
        return Unidade::find($id);
    }
}
