<?php

namespace App\Http\Controllers\Api;

use App\Models\Codigoitem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class CodigoitemController extends Controller
{

    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {


            $results = Codigoitem::where('descricao', 'LIKE', '%'.strtoupper($search_term).'%')
                // ->orWhere('nome', 'LIKE', '%'.strtoupper($search_term).'%')
                // ->orWhere('nomeresumido', 'LIKE', '%'.strtoupper($search_term).'%')
                ->paginate(10);



            // $results = Unidade::where('codigo', 'LIKE', '%'.strtoupper($search_term).'%')
            //     ->orWhere('nome', 'LIKE', '%'.strtoupper($search_term).'%')
            //     ->orWhere('nomeresumido', 'LIKE', '%'.strtoupper($search_term).'%')
            //     ->paginate(10);
        }
        else
        {
            $results = Codigoitem::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Codigoitem::find($id);
    }

}
