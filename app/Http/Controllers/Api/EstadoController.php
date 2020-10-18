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

        $options = Estado::query();

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
