<?php

namespace App\Http\Controllers\Api;

use App\Models\Catmatseritem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatmatseritemController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Catmatseritem::query();

        // if no category has been selected, show no options
        if (!$form['grupo_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['grupo_id']) {
            $options = $options->where('grupo_id', $form['grupo_id'])
                ->orderBy('descricao');
        }

        if ($search_term) {
            $results = $options->Where('descricao', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('codigo_siasg', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orderBy('descricao')
                ->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $options->paginate(10);
    }

    public function show($id)
    {
        return Catmatseritem::find($id);
    }
}
