<?php

namespace App\Http\Controllers\Api;

use App\Models\OrgaoSubcategoria;
use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class OrgaosubcategoriaController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = OrgaoSubcategoria::query();

        // if no category has been selected, show no options
        if (!$form['categoria_id']) {
            return [];
        }


        $unidade = Unidade::find(session()->get('user_ug_id'));


        // if a category has been selected, only show articles in that category
        if ($form['categoria_id']) {
            $options = $options->where('orgao_id', $unidade->orgao->id)
                ->where('categoria_id', $form['categoria_id'])
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
        return OrgaoSubcategoria::find($id);
    }
}
