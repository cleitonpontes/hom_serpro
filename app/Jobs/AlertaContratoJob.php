<?php

namespace App\Jobs;

use App\Models\Contrato;
use App\Models\Unidade;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AlertaContratoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $dia = date('d');

        $unidades_diario = Unidade::whereHas('configuracao', function ($c) {
            $c->where('email_diario', true);
        })
            ->where('situacao', true)
            ->where('tipo', 'E')
            ->get();

        $unidades_mensal = Unidade::whereHas('configuracao', function ($c) {
            $c->where('email_mensal', true);
        })
            ->where('situacao', true)
            ->where('tipo', 'E')
            ->get();

        $contratos_diario = $this->buscaContratosDiario($unidades_diario->configuracao->email_diario_periodicidade);


        if ($unidades_mensal->configuracao->email_mensal_dia == $dia) {
            $contratos_mensal = $unidades_mensal->contratos();

            $users = [];
            foreach ($contratos_mensal as $cm) {
                $responsaveis = $cm->responsaveis();
                foreach ($responsaveis as $responsavel) {
                    if($responsavel->situacao == true){
//                        $users[] = ;
                    }
                }
            }


            $usuarios = $contratos_mensal->responsaveis();
            //notifica usuarios


        }


        $usuarios_d = $this->buscaUsuarios($contratos_diario);


    }

    public function buscaUsuarios(Contrato $contratos)
    {
        $usuarios = [];
        foreach ($contratos as $contrato) {
            $usuarios[] = $contrato->responsaveis()->toArray();
        }

        return $usuarios;
    }

}
