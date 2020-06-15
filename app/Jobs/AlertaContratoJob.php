<?php

namespace App\Jobs;

use App\Models\BackpackUser;
use App\Models\Contrato;
use App\Models\Contratoresponsavel;
use App\Models\Unidade;
use App\Notifications\RotinaAlertaContratoDiarioNotification;
use App\Notifications\RotinaAlertaContratoNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
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

    /**
     * Rotina para envio de email diário às unidades que optam pelo recebimento deste tipo de mensagem, em suas
     * configurações, sobre os contratos com antecedência de vencimentos de acordo com as periodicidades escolhidas,
     * aos usuários responsáveis com cópia à chefia e/ou ordenadores de despesa.
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function emailDiario()
    {
        $unidades = $this->retornaUnidadesQueEnviamEmailDiario();

        foreach($unidades as $unidade) {
            $hoje = date('Y-m-d');
            $vencimentos = [];

            $prazos = $this->retornaPrazosTratados($unidade->configuracao->email_diario_periodicidade);

            foreach($prazos as $prazo) {
                $venc = date('Y-m-d', strtotime('+' . $prazo . ' days', strtotime($hoje)));
                $vencimentos[$prazo] = $venc;
            }

            $contratos = $this->retornaContratosDaUnidade($unidade, $vencimentos);

            foreach($contratos as $contrato) {
                $dtAgora = new \DateTime($hoje);
                $dtFim = new \DateTime($contrato->vigencia_fim);
                $qtdeDias = $dtFim->diff($dtAgora)->days;

                $usuarios = $this->retornaUsuariosDaUnidadeEDoContrato($unidade, $contrato);

                $primeiroUsuario = array_shift($usuarios);

                $dadosEmail = $this->retornaDadosParaEmail($unidade, $qtdeDias, $usuarios);
                $primeiroUsuario->notify(new RotinaAlertaContratoDiarioNotification($dadosEmail, $contrato));
            }
        }
    }












    public function extratoMensal()
    {
        $dia = date('d');
        $unidades = $this->retornaUnidadesQueEnviamEmailMensal($dia);

        foreach($unidades as $unidade) {
            $usuariosId = $this->retornaUsuariosResponsaveisUnicosPorContratosDaUnidade($unidade);
            $contratosTodos = $this->retornaContratosPorUsuariosResponsaveisDaUnidade($usuariosId);

            foreach($usuariosId as $usuarioId) {
                $contratos = array_filter($contratosTodos, function($dados) use($usuarioId) {
                    return $dados['user_id'] == $usuarioId;
                });

                $usuario = BackpackUser::find($usuarioId);
                $usuario->notify('aaa');

                dd($contratos, $usuarioId, $usuario->toArray());



                dd($contrato, 'fim');

                dd($contrato, 'fim');

                dd($usuario, $contrato, $contratosTodos);
            }


            dd($contratos);






            $contratos = $this->retornaContratosDaUnidade($unidade);

            foreach ($contratos as $contrato) {
                $usuarios = $this->retornaUsuariosDaUnidadeEDoContrato($unidade, $contrato);

                $primeiroUsuario = array_shift($usuarios);
                // $primeiroUsuario->notify(new RotinaAlertaContratoDiarioNotification($dadosEmail, $contrato));
                dd($usuarios, $primeiroUsuario->toArray(), $contrato->toArray());
            }



            dd($unidade->toArray(), $unidade->configuracao->toArray(), $contratos);
        }








        /*
        $dados_email['textobase'] = mb_convert_encoding($unidade_mensal->configuracao->email_mensal_texto,'UTF-8','UTF-8');
        $dados_email['nomerotina'] = 'Extrato Mensal';
        $dados_email['telefones'] = ($unidade_mensal->configuracao->telefone2) ? $unidade_mensal->configuracao->telefone1 . ' / ' . $unidade_mensal->configuracao->telefone2 : $unidade_mensal->configuracao->telefone1;

        foreach ($users as $user) {
            $contratos_user = Contrato::whereHas('responsaveis', function ($r) use ($user) {
                $r->where('user_id', $user->id);
            })
                ->orderBy('vigencia_fim', 'DESC')
                ->get();
            $dados_email['texto'] = str_replace('!!nomeresponsavel!!', $user->name, $dados_email['textobase']);
            $user->notify(new RotinaAlertaContratoNotification($user, $dados_email, $contratos_user));
        }
        */
    }

    /**
     * Retorna unidades executoras ativas que enviam email
     *
     * @return object @dados
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaUnidadesQueEnviamEmailDiario()
    {
        $modelo = Unidade::whereHas('configuracao', function ($configuracao) {
            $configuracao->where('email_diario', true);
        });
        $modelo->where('situacao', true);
        $modelo->where('tipo', 'E');

        return $modelo->get();
    }

    /**
     * Retorna unidades executoras ativas que enviam email
     *
     * @param int $dia
     * @return object @dados
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaUnidadesQueEnviamEmailMensal($dia)
    {
        $modelo = Unidade::whereHas('configuracao', function ($configuracao) use ($dia) {
            $configuracao->where('email_mensal', true);
            $configuracao->where('email_mensal_dia', $dia);
        });
        $modelo->where('situacao', true);
        $modelo->where('tipo', 'E');

        return $modelo->get();
    }

    /**
     * Retorna contratos da @unidade com final da vigência = $vencimentos
     *
     * @param object $unidade
     * @param array $vencimentos
     * @return object @dados
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratosDaUnidade($unidade, $vencimentos = [])
    {
        $contratos = $unidade->contratos();
        $contratos->distinct('id');
        $contratos->orderBy('vigencia_fim', 'DESC');
        $contratos->where('situacao', true);
        // $contratos->select('id', 'numero', 'vigencia_fim');

        if ($vencimentos) {
            $contratos->whereIn('vigencia_fim', $vencimentos);
        }

        return $contratos->get();
    }

    /**
     * Retorna usuários responsáveis do @contrato, bem como, a chefia e ordenadores de despesa da @unidade
     *
     * @param object $unidade
     * @param object $contrato
     * @return array $usuariosUnicos
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaUsuariosDaUnidadeEDoContrato($unidade, $contrato)
    {
        $usuarios = [];

        // Retorna usuários responsáveis do contrato...
        $responsaveis = $contrato->responsaveis;

        foreach ($responsaveis as $responsavel) {
            if ($responsavel->situacao == true) {
                $usuarios[] = $responsavel->user;
            }
        }

        // ...e os usuários (de 1 a 4) da configuração da unidade
        $config = $unidade->configuracao;

        if ($config->user1) {
            $usuarios[] = $config->user1;
        }

        if ($config->user2) {
            $usuarios[] = $config->user2;
        }

        if ($config->user3) {
            $usuarios[] = $config->user3;
        }

        if ($config->user4) {
            $usuarios[] = $config->user4;
        }

        // Elimina usuários 'repetidos'!
        $usuariosUnicos = array_unique($usuarios);

        return $usuariosUnicos;
    }

    private function retornaContratosPorUsuariosResponsaveisDaUnidade($usuarios)
    {
        // Contratos por usuários
        $modeloContrato = Contratoresponsavel::select(
            'user_id',
            'U.name',
            'U.email',
            'contrato_id',
            'C.numero',
            'C.processo',
            'F.cpf_cnpj_idgener',
            'F.nome',
            'C.objeto',
            'C.valor_global',
            'C.vigencia_inicio',
            'C.vigencia_fim'
        );

        $modeloContrato->join('users AS U', 'U.id', '=', 'contratoresponsaveis.user_id');
        $modeloContrato->join('contratos AS C', 'C.id', '=', 'contratoresponsaveis.contrato_id');
        $modeloContrato->join('fornecedores AS F', 'F.id', '=', 'C.fornecedor_id');

        $modeloContrato->whereIn('user_id', $usuarios);
        $modeloContrato->where('contratoresponsaveis.situacao', true);
        $modeloContrato->where('C.situacao', true);

        $modeloContrato->orderBy('user_id');
        $modeloContrato->orderBy('C.vigencia_fim', 'desc');

        $modeloContrato->distinct('user_id', 'contrato_id');

        return $modeloContrato->get()->toArray();
    }

    private function retornaUsuariosResponsaveisUnicosPorContratosDaUnidade($unidade)
    {
        // Id da unidade
        $unidadeId = $unidade->id;

        // Usuários responsáveis únicos...
        $modeloUsuario = Contratoresponsavel::select('user_id');

        $modeloUsuario->join('users AS U', 'U.id', '=', 'user_id');
        $modeloUsuario->join('contratos AS C', 'C.id', '=', 'contrato_id');

        $modeloUsuario->where('contratoresponsaveis.situacao', true);
        $modeloUsuario->where('C.situacao', true);
        $modeloUsuario->where('C.unidade_id', $unidadeId);

        $modeloUsuario->distinct('user_id');

        return $modeloUsuario->pluck('user_id')->toArray();
    }

    /**
     * Retorna dados diversos para montagem de email para envio
     *
     * @param object $unidade
     * @param number $qtdeDias
     * @param array $usuarios
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaDadosParaEmail($unidade, $qtdeDias = 0, $usuarios)
    {
        // Prepara dados para envio do email
        $rotina = 'Contratos à vencer em: ' . $qtdeDias . ' Dias!';

        $textoDiario = $unidade->configuracao->email_diario_texto;
        $textoConvertido = mb_convert_encoding($textoDiario, 'UTF-8', 'UTF-8');
        $texto = str_replace('!!nomeresponsavel!!', 'Responsável', $textoConvertido);

        $telefones = $unidade->configuracao->telefone1;
        $telefones .= ($unidade->configuracao->telefone2) ? ' / ' . $unidade->configuracao->telefone2 : '';

        // Montagem do array
        $dadosEmail['nomerotina'] = $rotina;
        $dadosEmail['telefones'] = $telefones;
        $dadosEmail['texto'] = $texto;

        // Usuários destinatários
        $dadosEmail['usuarios'] = $usuarios;

        return $dadosEmail;
    }

    /**
     * Retorna datas dos prazos de vencimentos conforme periodicidades
     *
     * @param array $periodicidades
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaPrazosTratados($periodicidades)
    {
        $separadoresInvalidos = [' ', '-', '_', '.', ',', '/'];
        $separadorValido = ';';

        // Retorna prazos para envio do email diário
        $periodosTratados = (!is_null($periodicidades) && $periodicidades != '') ? $periodicidades : '';

        foreach ($separadoresInvalidos as $caracter) {
            $periodosTratados = str_replace($caracter, $separadorValido, $periodosTratados);
        }

        $prazos = explode($separadorValido, $periodosTratados);

        return $prazos;
    }

}
