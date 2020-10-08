<?php

namespace App\Http\Controllers\Api;

use App\Models\AmparoLegal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AmparoLegalController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = AmparoLegal::select(
            ['id',
                DB::raw("ato_normativo ||
                        case when (artigo is not null)  then ' - Artigo: ' || artigo else '' end ||
                        case when (paragrafo is not null)  then ' - Parágrafo: ' || paragrafo else '' end ||
                        case when (inciso is not null)  then ' - Inciso: ' || inciso else '' end ||
                        case when (alinea is not null)  then ' - Alinea: ' || alinea else '' end
                        as amparo")]);

        // if no category has been selected, show no options
        if (!$form['modalidade_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['modalidade_id']) {
            $options = $options->where('modalidade_id', $form['modalidade_id']);
        }

        if ($search_term) {
            return $options->where('nome', 'ilike', '%' . strtoupper($search_term) . '%')
                ->orderBy('nome')
                ->paginate(10);
        }

        return $options->paginate(10);
    }

    public function show($id)
    {
        return AmparoLegal::find($id);
    }
}
