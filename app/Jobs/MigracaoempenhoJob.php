<?php

namespace App\Jobs;

use App\Models\Empenho;
use App\Models\Empenhodetalhado;
use App\Models\Fornecedor;
use App\Models\Naturezadespesa;
use App\Models\Naturezasubitem;
use App\Models\Planointerno;
use App\Models\Unidade;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigracaoempenhoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $unidades = Unidade::where('tipo', 'E')
            ->get();

        $ano = '2018';

        foreach ($unidades as $unidade) {
            $migracao_url = config('migracao.api_sta');
            $dados = json_decode(file_get_contents($migracao_url . '/api/empenho/ano/' . $ano . '/ug/' . $unidade->codigo),
                true);

            foreach ($dados as $d) {

                $credor = $this->buscaFornecedor($d);

                if ($d['picodigo']) {
                    $pi = $this->buscaPi($d);
                }

                $naturezadespesa = Naturezadespesa::where('codigo', $d['naturezadespesa'])
                    ->first();

                $empenho = Empenho::where('numero', '=', $d['numero'])
                    ->where('unidade_id', '=', $unidade->id)
                    ->where('fornecedor_id', '=', $credor->id)
                    ->where('planointerno_id', '=', $pi->id)
                    ->where('naturezadespesa_id', '=', $naturezadespesa->id)
                    ->first();

                if (!$empenho) {
                    $empenho = Empenho::create([
                        'numero' => $d['numero'],
                        'unidade_id' => $unidade->id,
                        'fornecedor_id' => $credor->id,
                        'planointerno_id' => $pi->id,
                        'naturezadespesa_id' => $naturezadespesa->id
                    ]);
                }

                foreach ($d['itens'] as $item) {

                    $naturezasubitem = Naturezasubitem::where('codigo', $item['subitem'])
                        ->where('naturezadespesa_id', $naturezadespesa->id)
                        ->first();

                    $empenhodetalhado = Empenhodetalhado::where('empenho_id', '=', $empenho->id)
                        ->where('naturezasubitem_id', '=', $naturezasubitem->id)
                        ->first();

                    if (!$empenhodetalhado) {
                        $empenhodetalhado = Empenhodetalhado::create([
                            'empenho_id' => $empenho->id,
                            'naturezasubitem_id' => $naturezasubitem->id
                        ]);
                    }
                }

            }

        }


    }

    public function buscaFornecedor($credor)
    {

        $fornecedor = Fornecedor::where('cpf_cnpj_idgener', '=', $credor['cpfcnpjugidgener'])
            ->first();

        if (!$fornecedor) {
            $tipo = 'JURIDICA';
            if (strlen($credor['cpfcnpjugidgener']) == 14) {
                $tipo = 'FISICA';
            } elseif (strlen($credor['cpfcnpjugidgener']) == 9) {
                $tipo = 'IDGENERICO';
            } elseif (strlen($credor['cpfcnpjugidgener']) == 6) {
                $tipo = 'UG';
            };

            $fornecedor = Fornecedor::create([
                'tipo_fornecedor' => $tipo,
                'cpf_cnpj_idgener' => $credor['cpfcnpjugidgener'],
                'nome' => strtoupper($credor['nome'])
            ]);
        }
        return $fornecedor;
    }

    public function buscaPi($pi)
    {

        $planointerno = Planointerno::where('codigo', '=', $pi['picodigo'])
            ->first();

        if (!$planointerno) {

            $planointerno = Planointerno::create([
                'codigo' => $pi['picodigo'],
                'descricao' => strtoupper($pi['pidescricao']),
                'situacao' => true
            ]);
        }
        return $planointerno;
    }
}
