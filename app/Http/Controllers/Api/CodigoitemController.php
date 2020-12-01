<?php

namespace App\Http\Controllers\Api;

use App\Models\Unidade;
use App\Models\Codigoitem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class CodigoitemController extends Controller
{
    /**
     * Retorna os codigoitens do código Modalidade Licitação, para Amparo Legal.
     *
     * @param $request
     * @return mixed
     * @author Márcio Vascs Donato <mvascs@gmail.com>
     */
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');
        if ($search_term)
        {
            $results = Codigoitem::select(
                'codigoitens.descres as descres', 'codigoitens.descricao as descricao', 'codigoitens.id', 'codigoitens.codigo_id'
            )
            ->join('codigos', 'codigos.id', '=', 'codigoitens.codigo_id')
            ->where('codigos.descricao', 'Modalidade Licitação')
            ->where('codigoitens.descricao', 'ilike', '%'.strtoupper($search_term).'%')
            ->paginate(10);
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
