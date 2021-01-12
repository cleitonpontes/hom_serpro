<?php

namespace App\Http\Controllers\Api;

use App\Models\Catmatseritem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
        $grupo_id = $request->tipo_id;
        $search_term = $request->input('q');
        $options = Catmatseritem::query();

        // if no category has been selected, show no options
        if (!$grupo_id) {
            return [];
        }
            $results = DB::table('catmatseritens AS c')
                ->select('c.*')
                ->join('catmatsergrupos AS cg', 'c.grupo_id', '=', 'cg.id')
                ->where('grupo_id', $grupo_id)
                ->whereNull('c.deleted_at')
                ->whereNull('cg.deleted_at');
                if($search_term){
                    $results->whereRaw("(c.descricao::text ilike '%$search_term%' or c.codigo_siasg::text ilike '%$search_term%')");
                }
                $results->orderBy('codigo_siasg');
            return $results->paginate(100);
    }
}
