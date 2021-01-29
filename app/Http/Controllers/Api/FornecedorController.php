<?php

namespace App\Http\Controllers\Api;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = Fornecedor::where('cpf_cnpj_idgener', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orderBy('nome', 'asc')
                ->paginate(10);
        } else {
            $results = Fornecedor::orderBy('nome', 'asc')
                ->paginate(10);
        }

        return $results;
    }

    public function suprido(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            return Fornecedor::where('cpf_cnpj_idgener', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->whereIn('tipo_fornecedor', ['FISICA', 'UG'])
                ->select([
                    'fornecedores.id',
                    DB::raw("fornecedores.nome || ' - ' || fornecedores.cpf_cnpj_idgener as cpf_cnpj_idgener")
                ])
                ->orderBy('nome', 'asc')
                ->paginate(10);
        }

        return Fornecedor::orderBy('nome', 'asc')
            ->paginate(10);
    }

    public function show($id)
    {
        return Fornecedor::find($id);
    }
}
