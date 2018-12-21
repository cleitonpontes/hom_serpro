<?php

namespace App\Http\Controllers\Api;

use App\Models\Contratoocorrencia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NovasituacaoController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Contratoocorrencia::whereHas('situacao', function ($query) {
            $query->whereHas('codigo', function ($query){
                $query->where('descricao', '=', 'Situação Ocorrência');
            })
                ->where('descricao', '=', 'Pendente')
                ->where('descricao', '=', 'Atendida Parcial');

        })
            ->where('contrato_id', '=', $form['contrato_id'])
            ->orderBy('numero')
            ->pluck('numero', 'id')
            ->toArray();

        // if no category has been selected, show no options
        if ($form['situacao'] != 132) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['category']) {
        $options = $options->where('category_id', $form['category']);
        }

        if ($search_term) {
            $results = $options->where('numero', 'LIKE', '%'.$search_term.'%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $options->paginate(10);
    }

    public function show($id)
    {
        return Article::find($id);
    }
}
