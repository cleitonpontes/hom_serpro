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
        $this->emailDiario();

    }

    public function emailDiario()
    {

        $unidades_diario = Unidade::whereHas('configuracao', function ($c) {
            $c->where('email_diario', true);
        })
            ->where('situacao', true)
            ->where('tipo', 'E')
            ->get();

        foreach ($unidades_diario as $unidade_diario) {

            $prazos = explode(';', $unidade_diario->configuracao->email_diario_periodicidade);
            $contratos = [];
            $data_vencimento = [];
            foreach ($prazos as $prazo) {
                $data_vencimento[$prazo] = date('Y-m-d', strtotime("+" . $prazo . " days", strtotime(date('Y-m-d'))));
                $contratos[$prazo] = $unidade_diario->contratos()
                    ->where('vigencia_fim', $data_vencimento[$prazo])
                    ->get();

            }

            $dados_email['texto'] = $unidade_diario->configuracao->email_diario_texto;

            $dados_email['telefones'] = ($unidade_diario->configuracao->telefone2) ? $unidade_diario->configuracao->telefone1 . ' / ' . $unidade_diario->configuracao->telefone2 : $unidade_diario->configuracao->telefone1;
            $dados_email['copiados']['user1'] = $unidade_diario->configuracao->user1;

            if ($unidade_diario->configuracao->user2_id) {
                $dados_email['copiados']['user2'] = $unidade_diario->configuracao->user2;
            }
            if ($unidade_diario->configuracao->user3_id) {
                $dados_email['copiados']['user3'] = $unidade_diario->configuracao->user3;
            }
            if ($unidade_diario->configuracao->user4_id) {
                $dados_email['copiados']['user4'] = $unidade_diario->configuracao->user4;
            }

            $users = [];
            foreach ($contratos as $key => $prazo) {
                $qtd_dias = $key;
                foreach ($prazo as $contrato) {
                    foreach ($contrato->responsaveis as $responsavel) {
                        if($responsavel->situacao == true){
                            $user = $responsavel->user;
                            $dados_email['nomerotina'] = 'Contratos à vencer em: ' . $qtd_dias . ' Dias!';
                            $dados_email['texto'] = str_replace('!!nomeresponsavel!!', $user->name, $dados_email['texto']);
                            $user->notify(new RotinaAlertaContratoNotification($user, $dados_email, $contrato));

                        }
                    }
                }
            }

        }


    }

    public
    function extratoMensal()
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
