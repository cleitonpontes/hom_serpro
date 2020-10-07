<?php

namespace App\Http\Controllers\Api;

use App\Models\Estado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EstadoController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Estado::query();
//        dd($options->paginate(10));

        // if no category has been selected, show no options
//        if (!$form['uf']) {
//            return [];
//        }
//
//        // if a category has been selected, only show articles in that category
//        if ($form['uf']) {
//            $options = $options->where('estado_id', $form['uf']);
//        }

        if ($search_term) {
            return $options->where('nome', 'ilike', '%' . strtoupper($search_term) . '%')
                ->orderBy('nome')
                ->paginate(10);
        }

        return $options->paginate(10);
    }

    public function show($id)
    {
        return Estado::find($id);
    }
}
