<?php

namespace App\Http\Controllers\Api;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Fornecedor::where('cpf_cnpj_idgener', 'LIKE', '%'.strtoupper($search_term).'%')
                ->orWhere('nome', 'LIKE', '%'.strtoupper($search_term).'%')
                ->paginate(10);
        }
        else
        {
            $results = Fornecedor::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Fornecedor::find($id);
    }
}
