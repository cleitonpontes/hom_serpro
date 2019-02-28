<?php

namespace App\Http\Controllers\Api;

use App\Models\Empenho;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmpenhoController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Empenho::query();

        // if no category has been selected, show no options
        if (! $form['fornecedor_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['fornecedor_id']) {
        $options = $options->where('fornecedor_id', $form['fornecedor_id'])
            ->where('unidade_id', '=', session()->get('user_ug_id'));
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
        return Empenho::find($id);
    }
}
