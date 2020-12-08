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
        if (!$form['tipo_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['tipo_id']) {
            $options = $options->whereHas('catmatsergrupo', function ($query) use ($form){
                $query->where('tipo_id', $form['tipo_id']);
            })
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

        return $results;
    }

    public function show($id)
    {
        return Catmatseritem::find($id);
    }


    public function itemPorTipo(Request $request)
    {
        $tipo_id = $request->tipo_id;
        $search_term = $request->input('q');
        $options = Catmatseritem::query();

        // if no category has been selected, show no options
        if (!$tipo_id) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        $options = $options->whereHas('catmatsergrupo', function ($query) use ($tipo_id){
            $query->where('tipo_id', $tipo_id);
        })->orderBy('descricao');

        if ($search_term) {
            $results = $options->Where('descricao', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('codigo_siasg', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orderBy('descricao')
                ->paginate(10);
        } else {
            $results = $options->paginate(100);
        }
        return $results;
    }
}
