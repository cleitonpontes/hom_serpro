<?php

namespace App\Http\Controllers\Api;

use App\Models\Empenho;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmpenhoController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = Empenho::query();

        // if no category has been selected, show no options
        if (!$form['fornecedor_id']) {
            return [];
        }

        // if a category has been selected, only show articles in that category
        if ($form['fornecedor_id']) {
            $options = $options->where('fornecedor_id', $form['fornecedor_id'])
                ->where('unidade_id', '=', session()->get('user_ug_id'));
        }

        if ($search_term) {
            $results = $options->where('numero', 'LIKE', '%' . $search_term . '%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return Empenho::find($id);
    }

    public function empenhosPorAnoUg(int $ano, int $unidade)
    {
        $empenhos_array = [];
        $empenhos = $this->buscaEmpenhosPorAnoUg($ano, $unidade);

        foreach ($empenhos as $empenho) {
            $empenhos_array[] = [
                'numero' => $empenho->numero,
                'unidade' => $empenho->unidade->codigo . ' - ' . $empenho->unidade->nomeresumido,
                'fornecedor' => $empenho->fornecedor->cpf_cnpj_idgener . ' - ' . $empenho->fornecedor->nome,
                'naturezadespesa' => $empenho->naturezadespesa->codigo . ' - ' . $empenho->naturezadespesa->descricao,
                'empenhado' => number_format($empenho->empenhado, 2, ',', '.'),
                'aliquidar' => number_format($empenho->aliquidar, 2, ',', '.'),
                'liquidado' => number_format($empenho->liquidado, 2, ',', '.'),
                'pago' => number_format($empenho->pago, 2, ',', '.'),
                'rpinscrito' => number_format($empenho->rpinscrito, 2, ',', '.'),
                'rpaliquidar' => number_format($empenho->rpaliquidar, 2, ',', '.'),
                'rpaliquidado' => number_format($empenho->rpaliquidado, 2, ',', '.'),
                'rppago' => number_format($empenho->rppago, 2, ',', '.'),
            ];
        }

        return json_encode($empenhos_array);
    }

    public function empenhosPorUg(int $unidade)
    {
        $empenhos_array = [];
        $empenhos = $this->buscaEmpenhosPorUg($unidade);

        foreach ($empenhos as $empenho) {
            $empenhos_array[] = [
                'numero' => $empenho->numero,
                'unidade' => $empenho->unidade->codigo . ' - ' . $empenho->unidade->nomeresumido,
                'fornecedor' => $empenho->fornecedor->cpf_cnpj_idgener . ' - ' . $empenho->fornecedor->nome,
                'naturezadespesa' => $empenho->naturezadespesa->codigo . ' - ' . $empenho->naturezadespesa->descricao,
                'empenhado' => number_format($empenho->empenhado, 2, ',', '.'),
                'aliquidar' => number_format($empenho->aliquidar, 2, ',', '.'),
                'liquidado' => number_format($empenho->liquidado, 2, ',', '.'),
                'pago' => number_format($empenho->pago, 2, ',', '.'),
                'rpinscrito' => number_format($empenho->rpinscrito, 2, ',', '.'),
                'rpaliquidar' => number_format($empenho->rpaliquidar, 2, ',', '.'),
                'rpaliquidado' => number_format($empenho->rpaliquidado, 2, ',', '.'),
                'rppago' => number_format($empenho->rppago, 2, ',', '.'),
            ];
        }

        return json_encode($empenhos_array);
    }

    private function buscaEmpenhosPorAnoUg(int $ano, int $unidade)
    {
        $empenhos = Empenho::whereHas('unidade', function ($q) use ($unidade){
            $q->where('codigo',$unidade);
        })
            ->where('numero', 'LIKE', $ano . 'NE%')
            ->get();

        return $empenhos;
    }

    private function buscaEmpenhosPorUg(int $unidade)
    {
        $empenhos = Empenho::whereHas('unidade', function ($q) use ($unidade){
            $q->where('codigo',$unidade);
        })
            ->get();

        return $empenhos;
    }

}
