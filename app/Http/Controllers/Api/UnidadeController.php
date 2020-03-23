<?php

namespace App\Http\Controllers\Api;

use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnidadeController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Unidade::where('codigo', 'LIKE', '%'.strtoupper($search_term).'%')
                ->orWhere('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->paginate(10);
        }
        else
        {
            $results = Unidade::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Unidade::find($id);
    }
}
