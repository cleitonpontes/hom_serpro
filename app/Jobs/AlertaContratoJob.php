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
        $unidades = $this->retornaUnidadesQueEnviamEmail();

        foreach($unidades as $unidade) {
            // Retorna prazos para envio do email diário
            $prazos = explode(';', $unidade->configuracao->email_diario_periodicidade);

            $vencimentos = [];

            $hoje = date('Y-m-d');
            //
            // Testes...
            //
            $hoje = '2020-06-04'; // 7 contratos [5275, 5347, 5469, 5487, 5691, 5791, 5794]
            // $hoje = '2020-06-10'; // 4 contratos [5587, 5630, 5713, 5820]

            foreach($prazos as $prazo) {
                $venc = date('Y-m-d', strtotime('+' . $prazo . ' days', strtotime($hoje)));
                $vencimentos[$prazo] = $venc;
            }

            // Retorna contratos da unidade com final da vigência = $vencimentos
            $contratos = $this->retornaContratosDaUnidade($unidade, $vencimentos);

            foreach($contratos as $contrato) {
                $dtAgora = new \DateTime($hoje);
                $dtFim = new \DateTime($contrato->vigencia_fim);
                $qtdeDias = $dtFim->diff($dtAgora)->days;

                foreach ($contrato->responsaveis as $responsavel) {
                    $situacaoUsuario = $responsavel->situacao;

                    if ($situacaoUsuario == true) {
                        $nomeUsuario = $responsavel->user->name;

                        // Monta dados para envio do email
                        $dadosEmail = $this->retornaDadosParaEmail($unidade, $qtdeDias, $nomeUsuario);

                        $user = $responsavel->user;
                        $user->notify(new RotinaAlertaContratoNotification($user, $dadosEmail, $contrato));
                    }
                }

                // Se tenha user1, user2, user3, user4...
                // Enviar um email cc para cada (ao invés de uma cópia por responsável
                // Nesse caso, deve-se remover a parte de Usuários em $this->retornaDadosParaEmail
            }
        }
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
                $dados_email['textobase'] = mb_convert_encoding($unidade_mensal->configuracao->email_mensal_texto,'UTF-8','UTF-8');
                $dados_email['nomerotina'] = 'Extrato Mensal';
                $dados_email['telefones'] = ($unidade_mensal->configuracao->telefone2) ? $unidade_mensal->configuracao->telefone1 . ' / ' . $unidade_mensal->configuracao->telefone2 : $unidade_mensal->configuracao->telefone1;

                $users = [];
                foreach ($contratos_mensal as $cm) {
                    $responsaveis = $cm->responsaveis()->get();
                    foreach ($responsaveis as $responsavel) {
                        if ($responsavel->situacao == true) {
                            $users[] = $responsavel->user;
                        }
                    }
                }

                $users = array_unique($users);

                foreach ($users as $user) {
                    $contratos_user = Contrato::whereHas('responsaveis', function ($r) use ($user) {
                        $r->where('user_id', $user->id);
                    })
                        ->orderBy('vigencia_fim', 'DESC')
                        ->get();
                    $dados_email['texto'] = str_replace('!!nomeresponsavel!!', $user->name, $dados_email['textobase']);
                    $user->notify(new RotinaAlertaContratoNotification($user, $dados_email, $contratos_user));
                }
            }
        }
    }










    private function retornaUnidadesQueEnviamEmail()
    {
        // Retorna unidades executoras ativas que enviam email
        $dados = Unidade::whereHas('configuracao', function ($config) {
            $config->where('email_diario', true);
        });
        $dados->where('situacao', true);
        $dados->where('tipo', 'E');

        return $dados->get();
    }

    private function retornaContratosDaUnidade($unidade, $vencimentos)
    {
        // Retorna contratos da unidade com final da vigência = $vencimentos
        $dados = $unidade->contratos();
        $dados->distinct('id');
        // $dados->select('id', 'numero', 'vigencia_fim');
        $dados->whereIn('vigencia_fim', $vencimentos);

        return $dados->get();
    }

    private function retornaDadosParaEmail($unidade, $qtdeDias = 0, $nomeUsuario = '')
    {
        // Preparações
        $rotina = 'Contratos à vencer em: ' . $qtdeDias . ' Dias!';

        $textoDiario = $unidade->configuracao->email_diario_texto;
        $textoConvertido = mb_convert_encoding($textoDiario, 'UTF-8', 'UTF-8');
        $texto = str_replace('!!nomeresponsavel!!', $nomeUsuario, $textoConvertido);

        $telefones = $unidade->configuracao->telefone1;
        $telefones .= ($unidade->configuracao->telefone2) ? ' / ' . $unidade->configuracao->telefone2 : '';

        // Montagem do array
        $dadosEmail['nomerotina'] = $rotina;
        $dadosEmail['telefones'] = $telefones;
        $dadosEmail['texto'] = $texto;

        // Usuários em cópia
        $user1 = $unidade->configuracao->user1;
        $user2 = $unidade->configuracao->user2;
        $user3 = $unidade->configuracao->user3;
        $user4 = $unidade->configuracao->user4;

        $dadosEmail['copiados']['user1'] = $user1;

        if ($user2) {
            $dadosEmail['copiados']['user2'] = $user2;
        }

        if ($user3) {
            $dadosEmail['copiados']['user3'] = $user3;
        }

        if ($user4) {
            $dadosEmail['copiados']['user4'] = $user4;
        }

        return $dadosEmail;
    }

}
