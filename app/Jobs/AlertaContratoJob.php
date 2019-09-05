<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\Contrato;
use App\Models\Unidade;
use App\Notifications\RotinaAlertaContratoNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use function foo\func;

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

        $this->extratoMensal();

//        $unidades_diario = Unidade::whereHas('configuracao', function ($c) {
//            $c->where('email_diario', true);
//        })
//            ->where('situacao', true)
//            ->where('tipo', 'E')
//            ->get();
//
//
//        $contratos_diario = $this->buscaContratosDiario($unidades_diario->configuracao->email_diario_periodicidade);
//
//        $usuarios_d = $this->buscaUsuarios($contratos_diario);


    }

    public function emailDiario()
    {

    }

    public function extratoMensal()
    {
        $dia = date('d');

        $dados_email = [];

        $unidades_mensal = Unidade::whereHas('configuracao', function ($c) {
            $c->where('email_mensal', true);
        })
            ->where('situacao', true)
            ->where('tipo', 'E')
            ->get();

        foreach ($unidades_mensal as $unidade_mensal) {
            if ($unidade_mensal->configuracao->email_mensal_dia == $dia) {
                $contratos_mensal = $unidade_mensal->contratos()->get();
                $dados_email['texto'] = $unidade_mensal->configuracao->email_mensal_texto;
                $dados_email['nomerotina'] = 'Extrato Mensal';
                $dados_email['telefones'] = ($unidade_mensal->configuracao->telefone2) ? $unidade_mensal->configuracao->telefone1 . ' / ' . $unidade_mensal->configuracao->telefone2 : $unidade_mensal->configuracao->telefone1;

                $users = [];
                foreach ($contratos_mensal as $cm) {
                    $responsaveis = $cm->responsaveis()->get();
                    foreach ($responsaveis as $responsavel) {
                        if ($responsavel->situacao == true) {
                            $users[] = $responsavel->user()->get();
                        }
                    }
                }

                foreach ($users as $users_colection) {
                    foreach ($users_colection as $user) {
                        $contratos_user = Contrato::whereHas('responsaveis', function ($r) use ($user) {
                            $r->where('user_id', $user->id);
                        })
                            ->orderBy('vigencia_fim', 'DESC')
                            ->get();

                        $dados_email['texto'] = str_replace('!!nomeresponsavel!!', $user->name, $dados_email['texto']);

                        $user->notify(new RotinaAlertaContratoNotification($user, $dados_email, $contratos_user));

                    }
                }
            }
        }
    }


}
