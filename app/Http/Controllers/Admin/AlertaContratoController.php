<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\AlertaContratoJob;
use App\Models\BackpackUser;
use App\Models\Contrato;
use App\Models\Contratoresponsavel;
use App\Models\Unidade;
use App\Notifications\RotinaAlertaContratoNotification;
use Illuminate\Routing\Controller;

class AlertaContratoController extends Controller
{
    public function enviaEmails()
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
                $contratoComoArray = [];
                $dtAgora = new \DateTime($hoje);
                $dtFim = new \DateTime($contrato['vigencia_fim']);
                $qtdeDias = $dtFim->diff($dtAgora)->days;

                $rotina = 'Contratos à vencer em: ' . $qtdeDias . ' Dias!';
                $dadosEmail = $this->retornaDadosParaEmail($unidade->configuracao, $rotina);

                $usuarios = $this->retornaUsuariosDaUnidadeEDoContrato($unidade, $contrato);
                $primeiroUsuario = array_shift($usuarios);

                $contratoComoArray[] = $contrato;
                $notificacao = new RotinaAlertaContratoNotification($dadosEmail, $contratoComoArray, $usuarios);

                AlertaContratoJob::dispatch($primeiroUsuario, $notificacao)->onQueue('email_diario');
            }
        }
    }

    /**
     * Rotina para envio de email mensal às unidades que optam pelo recebimento deste tipo de mensagem, em suas
     * configurações, sobre os contratos aos usuários responsáveis, bem como novo envio à chefia e/ou ordenadores de
     * despesa, contendo os instrumentos ativos daquela unidade.
     *
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function extratoMensal()
    {
        $dia = date('d');
        $unidades = $this->retornaUnidadesQueEnviamEmailMensal($dia);

        foreach($unidades as $unidade) {
            $unidadeId = $unidade->id;

            $rotina = 'Extrato Mensal';
            $dadosEmail = $this->retornaDadosParaEmail($unidade->configuracao, $rotina, 'email_mensal_texto');

            $usuariosDeChefia = $this->retornaUsuariosChefiaDaUnidade($unidade);
            $chefias = $this->retornaChefiasIds($usuariosDeChefia);
            $usuariosId = $this->retornaUsuariosResponsaveisUnicosPorContratosDaUnidade($unidadeId, $chefias);

            $contratosResponsavel = $this->retornaContratosPorUsuariosResponsaveisDaUnidade($unidadeId, $usuariosId);

            foreach($usuariosId as $usuarioId) {
                $contratosFiltrados = [];
                foreach($contratosResponsavel as $contrato) {
                    if ($contrato->user_id == $usuarioId) {
                        $contratosFiltrados[] = $contrato;
                    }
                }

                $usuario = BackpackUser::find($usuarioId);
                $notificacao = new RotinaAlertaContratoNotification($dadosEmail, $contratosFiltrados);

                AlertaContratoJob::dispatch($usuario, $notificacao)->onQueue('email_mensal');
            }

            $contratosChefias = $this->retornaContratosTodosDaUnidade($unidadeId);

            $primeiroChefe = array_shift($usuariosDeChefia);
            $notificacaoChefia = new RotinaAlertaContratoNotification(
                $dadosEmail, $contratosChefias, $usuariosDeChefia
            );

            AlertaContratoJob::dispatch($primeiroChefe, $notificacaoChefia)->onQueue('email_mensal');
        }
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
     * Retorna contratos da @unidade com final da vigência = $vencimentos, se informado
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

    /**
     * Retorna os contratos dos $usuarios responsáveis da $unidadeId
     *
     * @param number $unidadeId
     * @param array $usuarios
     * @return mixed
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratosPorUsuariosResponsaveisDaUnidade($unidadeId, $usuarios)
    {
        $modeloContrato = $this->retornaModelContratosDaUnidade($unidadeId);

        $modeloContrato->whereIn('user_id', $usuarios);

        return $modeloContrato->get();
    }

    /**
     * Retorna todos os contratos ativos da $unidadeId
     *
     * @param $unidadeId
     * @return mixed
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaContratosTodosDaUnidade($unidadeId)
    {
        $modeloContrato = Contrato::select(
            'contratos.id',
            'numero',
            'processo',
            'F.cpf_cnpj_idgener',
            'F.nome',
            'objeto',
            'valor_global',
            'vigencia_inicio',
            'vigencia_fim'
        );

        $modeloContrato->join('fornecedores AS F', 'F.id', '=', 'fornecedor_id');

        $modeloContrato->where('situacao', true);
        $modeloContrato->where('unidade_id', $unidadeId);

        $modeloContrato->orderBy('vigencia_fim', 'desc');

        return $modeloContrato->get();
    }

    /**
     * Retorna modelo dos contratos ativos da $unidadeId.
     *
     * @param $unidadeId
     * @return mixed
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaModelContratosDaUnidade($unidadeId)
    {
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

        $modeloContrato->where('contratoresponsaveis.situacao', true);
        $modeloContrato->where('C.situacao', true);
        $modeloContrato->where('C.unidade_id', $unidadeId);

        $modeloContrato->orderBy('user_id');
        $modeloContrato->orderBy('C.vigencia_fim', 'desc');

        $modeloContrato->distinct('user_id', 'contrato_id');

        return $modeloContrato;
    }

    /**
     * Retorna os usuários de chefia, ordenadores de despesas e seus substitutos
     *
     * @param object $unidade
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaUsuariosChefiaDaUnidade($unidade)
    {
        $usuariosDeChefia = [];

        $config = $unidade->configuracao;

        if ($config->user1) {
            $usuariosDeChefia[] = $config->user1;
        }

        if ($config->user2) {
            $usuariosDeChefia[] = $config->user2;
        }

        if ($config->user3) {
            $usuariosDeChefia[] = $config->user3;
        }

        if ($config->user4) {
            $usuariosDeChefia[] = $config->user4;
        }

        return $usuariosDeChefia;
    }

    /**
     * Retorna array com ids dos usuários de chefia e/ou ordenadores de despesas
     *
     * @param object $usuariosDeChefia
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaChefiasIds($usuariosDeChefia)
    {
        $chefias = [];
        foreach($usuariosDeChefia as $chefe) {
            $chefias[] = $chefe->id;
        }

        return $chefias;
    }

    /**
     * Retorna usuários responsáveis únicos por contrato da $unidadeId
     *
     * @param number $unidadeId
     * @param array $chefias
     * @return mixed
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaUsuariosResponsaveisUnicosPorContratosDaUnidade($unidadeId, $chefias)
    {
        $modeloUsuario = Contratoresponsavel::select('user_id');

        $modeloUsuario->join('users AS U', 'U.id', '=', 'user_id');
        $modeloUsuario->join('contratos AS C', 'C.id', '=', 'contrato_id');

        $modeloUsuario->where('contratoresponsaveis.situacao', true);
        $modeloUsuario->where('C.situacao', true);
        $modeloUsuario->where('C.unidade_id', $unidadeId);
        $modeloUsuario->whereNotIn('contratoresponsaveis.user_id', $chefias);

        $modeloUsuario->distinct('user_id');

        return $modeloUsuario->pluck('user_id')->toArray();
    }

    /**
     * Retorna dados diversos para montagem de email para envio
     *
     * @param object $configuracaoUnidade
     * @param string $rotina
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    private function retornaDadosParaEmail($configuracaoUnidade, $rotina, $campoTexto = 'email_diario_texto')
    {
        // Prepara dados para envio do email
        $textoDiario = $configuracaoUnidade->$campoTexto;
        $textoConvertido = mb_convert_encoding($textoDiario, 'UTF-8', 'UTF-8');
        $texto = str_replace('!!nomeresponsavel!!', 'Responsável', $textoConvertido);

        $telefones = $configuracaoUnidade->telefone1;
        $telefones .= ($configuracaoUnidade->telefone2) ? ' / ' . $configuracaoUnidade->telefone2 : '';

        // Montagem do array
        $dadosEmail['nomerotina'] = $rotina;
        $dadosEmail['telefones'] = $telefones;
        $dadosEmail['texto'] = $texto;

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
