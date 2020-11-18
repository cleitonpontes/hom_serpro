<?php

namespace App\Jobs;

use App\Http\Traits\Busca;
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
    use Busca, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    protected $ug_codigo;
    protected $ano;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $ug_codigo, $ano)
    {
        $this->ug_codigo = $ug_codigo;
        $this->ano = $ano;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $unidade = Unidade::where('codigo', $this->ug_codigo)->first();
        $migracao_url = config('migracao.api_sta');

        $url = $migracao_url . '/api/empenho/ano/' . $this->ano . '/ug/' . $unidade->codigo . '/dia';

        $dados = (env('APP_ENV', 'production') === 'production')
            ? $this->buscaDadosFileGetContents($url)
            : $this->buscaDadosCurl($url);

        foreach ($dados as $d) {

            $credor = $this->buscaFornecedor($d);

            if ($d['picodigo'] != "") {
                $pi = $this->buscaPi($d);
            }

            $pi_id = null;
            if (isset($pi->id)) {
                $pi_id = $pi->id;
            }

            $naturezadespesa = Naturezadespesa::where('codigo', $d['naturezadespesa'])
                ->first();

            $empenho = Empenho::where('numero', '=', trim($d['numero']))
                ->where('unidade_id', '=', $unidade->id)
                ->withTrashed()
                ->first();

            if (!isset($empenho->id)) {
                $empenho = Empenho::create([
                    'numero' => trim($d['numero']),
                    'unidade_id' => $unidade->id,
                    'fornecedor_id' => $credor->id,
                    'planointerno_id' => $pi_id,
                    'naturezadespesa_id' => $naturezadespesa->id,
                    'fonte' => trim($d['fonte']),
                ]);
            } else {
                $empenho->fornecedor_id = $credor->id;
                $empenho->planointerno_id = $pi_id;
                $empenho->naturezadespesa_id = $naturezadespesa->id;
                $empenho->fonte = trim($d['fonte']);
                $empenho->deleted_at = null;
                $empenho->save();
            }

            foreach ($d['itens'] as $item) {

                $naturezasubitem = Naturezasubitem::where('codigo', $item['subitem'])
                    ->where('naturezadespesa_id', $naturezadespesa->id)
                    ->first();

                $empenhodetalhado = Empenhodetalhado::where('empenho_id', '=', $empenho->id)
                    ->where('naturezasubitem_id', '=', $naturezasubitem->id)
                    ->first();

                if (!isset($empenhodetalhado)) {
                    $empenhodetalhado = Empenhodetalhado::create([
                        'empenho_id' => $empenho->id,
                        'naturezasubitem_id' => $naturezasubitem->id
                    ]);
                }
            }
        }
    }


    public function buscaFornecedor($credor)
    {

        $fornecedor = Fornecedor::where('cpf_cnpj_idgener', '=', $credor['cpfcnpjugidgener'])
            ->first();

        if (!$fornecedor) {
            $tipoFornecedor = [14 => 'FISICA', 9 => 'IDGENERICO', 6 => 'UG'];

            $tipo = $tipoFornecedor[strlen($credor['cpfcnpjugidgener'])] ?? 'JURIDICA';

            $fornecedor = Fornecedor::create([
                'tipo_fornecedor' => $tipo,
                'cpf_cnpj_idgener' => $credor['cpfcnpjugidgener'],
                'nome' => strtoupper($credor['nome'])
            ]);

        } elseif ($fornecedor->nome != strtoupper(trim($credor['nome']))) {
            $fornecedor->nome = strtoupper(trim($credor['nome']));
            $fornecedor->save();
        }

        return $fornecedor;
    }

    public function buscaPi($pi)
    {

        $planointerno = Planointerno::where('codigo', '=', $pi['picodigo'])
            ->first();

        if (!$planointerno) {
            return Planointerno::create([
                'codigo' => $pi['picodigo'],
                'descricao' => strtoupper($pi['pidescricao']),
                'situacao' => true
            ]);
        }

        if ($planointerno->descricao != strtoupper($pi['pidescricao'])) {
            $planointerno->descricao = strtoupper($pi['pidescricao']);
            $planointerno->save();
        }
        return $planointerno;
    }

}
