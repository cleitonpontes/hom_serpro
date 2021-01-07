<?php

namespace App\Http\Controllers\Api;

use App\Models\Contratohistorico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContratohistoricoController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Contratohistorico::where('numero', 'LIKE', '%'.strtoupper($search_term).'%')
                ->paginate(10);
        }
        else
        {
            $results = Contratohistorico::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Contratohistorico::find($id);
    }
}
