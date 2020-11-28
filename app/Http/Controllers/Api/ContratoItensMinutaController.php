<?php

namespace App\Http\Controllers\Api;

use App\Models\AmparoLegal;
use App\Models\Catmatseritem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Route;

class ContratoItensMinutaController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = AmparoLegal::select([
            'id',
            DB::raw("ato_normativo ||
                    case when (artigo is not null)  then ' - Artigo: ' || artigo else '' end ||
                    case when (paragrafo is not null)  then ' - Parágrafo: ' || paragrafo else '' end ||
                    case when (inciso is not null)  then ' - Inciso: ' || inciso else '' end ||
                    case when (alinea is not null)  then ' - Alinea: ' || alinea else '' end
                    as campo_api_amparo")
        ]);

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


    public function buscarItensModal(Request $request)
    {
        $tipo_id = Route::current()->parameter('tipo_id');

        $itens = Catmatseritem::where('grupo_id',$tipo_id)->pluck('descricao','id')->toArray();

        return $itens;
    }


    public function inserirIten(Request $request)
    {
        $cod_unidade = Route::current()->parameter('cod_unidade');
        $contacorrente = Route::current()->parameter('contacorrente');

        $saldoExiste = SaldoContabil::where('conta_corrente',$contacorrente)->first();
        if(is_null($saldoExiste)) {
            DB::beginTransaction();
            try {
                $modSaldo = new SaldoContabil();
                $modSaldo->unidade_id = $unidade->id;
                $modSaldo->ano = $ano;
                $modSaldo->conta_contabil = $contacontabil;
                $modSaldo->conta_corrente = $contacorrente;
                $modSaldo->saldo = (string)$contaSiafi->resultado[4];
                $modSaldo->save();
                DB::commit();
                $retorno['resultado'] = true;
            } catch (\Exception $exc) {
                DB::rollback();
            }
        }else{
            $retorno['resultado'] = false;
        }

        return json_encode($retorno);
    }

}
