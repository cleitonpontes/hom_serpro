<?php

namespace App\Jobs;

use App\Models\Codigoitem;
use App\Models\Siasgcompra;
use App\Models\Unidade;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CargaInicialSiasgComprasJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

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
        $file = fopen(env('CARGA_INICIAL_COMPRAS'), "r");
        $compras = new Siasgcompra;
        $dados = [];
        while (!feof($file)) {
            $line = fgets($file);

            $unidade = Unidade::where('codigosiasg', substr($line, 0, 6))
                ->first();

            $modalidade = Codigoitem::whereHas('codigo', function ($c) {
                $c->where('descricao', '=', 'Modalidade Licitação');
            })
                ->where('descres', substr($line, 6, 2))
                ->first();

            $numero = substr($line, 8, 5);
            $ano = substr($line, 13, 4);

            if(isset($unidade->id)){
                $busca = $compras->where('unidade_id', $unidade->id)
                    ->where('modalidade_id', $modalidade->id)
                    ->where('ano', $ano)
                    ->where('numero', $numero)
                    ->first();

                if (!isset($busca->id)) {
                    $dados = [
                        'unidade_id' => $unidade->id,
                        'modalidade_id' => $modalidade->id,
                        'ano' => $ano,
                        'numero' => $numero,
                        'situacao' => 'Pendente'
                    ];

                    $compranova = new Siasgcompra();
                    $compranova->fill($dados);
                    $compranova->save();
                }
            }
        }
        fclose($file);
    }
}
