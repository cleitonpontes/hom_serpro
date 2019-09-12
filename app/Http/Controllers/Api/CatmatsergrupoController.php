<?php

namespace App\Http\Controllers\Api;

use App\Models\Catmatsergrupo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatmatsergrupoController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Catmatsergrupo::query();

        // if no category has been selected, show no options
        if (!$form['tipo_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['tipo_id']) {
            $options = $options->where('tipo_id', $form['tipo_id'])
                ->orderBy('descricao');
        }

        if ($search_term) {
            $results = $options->where('descricao', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orderBy('descricao')
                ->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Catmatsergrupo::find($id);
    }
}
