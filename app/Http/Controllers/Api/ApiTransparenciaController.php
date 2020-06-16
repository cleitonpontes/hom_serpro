<?php

namespace App\Http\Controllers\Api;

use App\Models\Contrato;
use App\Models\Fornecedor;
use App\Models\Orgao;
use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function foo\func;

class ApiTransparenciaController extends Controller
{
    public function orgaos(Request $request)
    {
        $search_term = $request->input('q');

        if ($search_term) {
            $results = Orgao::where('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->orWhere('codigo', 'LIKE', '%' . strtoupper($search_term) . '%')
                ->whereHas('unidades', function ($u) {
                    $u->whereHas('contratos', function ($c) {
                        $c->where('situacao', true);
                    });
                })
                ->orderBy('codigo', 'ASC')
                ->paginate(50);
        } else {
            $results = Orgao::whereHas('unidades', function ($u) {
                $u->whereHas('contratos', function ($c) {
                    $c->where('situacao', true);
                });
            })
                ->orderBy('codigo', 'ASC')
                ->paginate(50);
        }

        return $results;
    }

    public function unidades(Request $request)
    {
        $orgao = $request->input('orgao');
        $search_term = $request->input('q');

        //caso exista busca
        if (!empty($search_term)) {

            //caso exista orgao
            if ($orgao) {
                return Unidade::whereHas('contratos', function ($c) {
                    $c->where('situacao', true);
                })
                    ->whereHas('orgao', function ($o) use ($orgao) {
                        $o->where('codigo', $orgao);
                    })
                    ->where(function ($query) use ($search_term) {
                        $query->where('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                            ->orWhere('codigo', 'LIKE', '%' . strtoupper($search_term) . '%');
                    })
                    ->orderBy('codigo', 'ASC')
                    ->paginate(50);
            }

            //caso nao exista orgao
            return Unidade::whereHas('contratos', function ($c) {
                $c->where('situacao', true);
            })
                ->where(function ($query) use ($search_term) {
                    $query->where('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                        ->orWhere('codigo', 'LIKE', '%' . strtoupper($search_term) . '%');
                })
                ->orderBy('codigo', 'ASC')
                ->paginate(50);
        }

        //caso nao exista busca

        //caso haja orgao
        if ($orgao) {
            return Unidade::whereHas('contratos', function ($c) {
                $c->where('situacao', true);
            })
                ->whereHas('orgao', function ($o) use ($orgao) {
                    $o->where('codigo', $orgao);
                })
                ->orderBy('codigo', 'ASC')
                ->paginate(50);
        }

        //caso não haja orgao
        return Unidade::whereHas('contratos', function ($c) {
            $c->where('situacao', true);
        })
            ->orderBy('codigo', 'ASC')
            ->paginate(50);

    }

    public function fornecedores(Request $request)
    {
        $unidade = $request->input('unidade');
        $search_term = $request->input('q');

        //caso exista busca
        if (!empty($search_term)) {

            //caso exista unidade
            if ($unidade) {
                return Fornecedor::whereHas('contratos', function ($c) use ($unidade) {
                    $c->whereHas('unidade', function ($u) use ($unidade) {
                        $u->where('codigo', $unidade);
                    })->where('situacao', true);
                })
                    ->where(function ($query) use ($search_term) {
                        $query->where('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                            ->orWhere('cpf_cnpj_idgener', 'LIKE', '%' . strtoupper($search_term) . '%');
                    })
                    ->orderBy('nome')
                    ->paginate(50);
            }

            //caso não exista unidade
            return Fornecedor::whereHas('contratos', function ($c) {
                $c->where('situacao', true);
            })
                ->where(function ($query) use ($search_term) {
                    $query->where('nome', 'LIKE', '%' . strtoupper($search_term) . '%')
                        ->orWhere('cpf_cnpj_idgener', 'LIKE', '%' . strtoupper($search_term) . '%');
                })
                ->orderBy('nome')
                ->paginate(50);

        }
        //caso nao exista busca

        //caso exista unidade

        if ($unidade) {

            return Fornecedor::whereHas('contratos', function ($c) use ($unidade) {
                $c->whereHas('unidade', function ($u) use ($unidade) {
                    $u->where('codigo', $unidade);
                })
                    ->where('situacao', true);
            })
                ->orderBy('nome')
                ->paginate(50);
        }

        //caso não exista unidade
        return Fornecedor::whereHas('contratos', function ($c) {
            $c->where('situacao', true);
        })
            ->orderBy('nome')
            ->paginate(50);

    }

    public function contratos(Request $request)
    {
        $fornecedor = $request->input('fornecedor');
        $search_term = $request->input('q');

        if (!empty($search_term)) {
            if (!empty($fornecedor)) {
                $results = Contrato::whereHas('fornecedor', function ($f) use ($fornecedor, $search_term) {
                    $f->where('cpf_cnpj_idgener', $fornecedor);
                })
                    ->where('situacao', true)
                    ->orWhere('numero', 'LIKE', '%' . strtoupper($search_term) . '%')
                    ->orderBy('numero')
                    ->paginate(50);
            } else {
                $results = Contrato::where('situacao', true)
                    ->orWhere('numero', 'LIKE', '%' . strtoupper($search_term) . '%')
                    ->orderBy('numero')
                    ->paginate(50);
            }
        } else {
            if ($fornecedor) {
                $results = Contrato::whereHas('fornecedor', function ($f) use ($fornecedor) {
                    $f->where('cpf_cnpj_idgener', $fornecedor);
                })
                    ->where('situacao', true)
                    ->orderBy('numero')
                    ->paginate(50);
            } else {
                $results = Contrato::where('situacao', true)
                    ->orderBy('numero')
                    ->paginate(50);
            }
        }

        return $results;
    }

}
