<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Jobs\MigracaoSistemaContaJob;
use App\Models\Contrato;
use App\Models\Contratohistorico;
use App\Models\MigracaoSistemaConta;
use App\Models\Orgaoconfiguracao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MigracaoSistemaContaController extends Controller
{
    public function index(int $orgaoconfiguracao_id)
    {
        $orgaoconfiguracao = Orgaoconfiguracao::find($orgaoconfiguracao_id);

        if (!isset($orgaoconfiguracao->id)) {
            \Alert::error('Configuração Inválida!')->flash();
            return redirect()->back();
        }

        if ($orgaoconfiguracao->api_migracao_conta_url and $orgaoconfiguracao->api_migracao_conta_token) {
            $retorno = $this->executaMigracao($orgaoconfiguracao);
        }

        if ($retorno == []) {
            \Alert::warning('Algo deu errado, entre em contato com o Suporte!')->flash();
            return redirect()->back();
        }

        \Alert::success('Migração de Dados em Andamento!')->flash();
        return redirect()->back();
    }

    private function executaMigracao(Orgaoconfiguracao $orgaoconfiguracao)
    {
        $retorno = [];

        $url = $this->montaUrl($orgaoconfiguracao, 'contratos');
        $base = new AdminController();
        $dados = $base->buscaDadosUrl($url);

        if ($dados == []) {
            return $retorno;
        }

        $i = 0;
        foreach ($dados as $dado) {

            $ndados = $base->buscaDadosUrl($dado);

            foreach ($ndados as $ndado){
                $contrato = new MigracaoSistemaConta();
                $retorno = $contrato->trataDadosMigracaoConta($ndado);
            }

            ($i==10) ? dd($retorno) : null;
            $i++;
//            MigracaoSistemaContaJob::dispatch($dado)->onQueue('migracaosistemaconta');
        }

        $retorno = true;

        return $retorno;
    }

    private function montaUrl(Orgaoconfiguracao $orgaoconfiguracao, string $tabela, int $id = null)
    {
        $url = '';

        if ($orgaoconfiguracao->api_migracao_conta_url and $orgaoconfiguracao->api_migracao_conta_token) {
            $url = $orgaoconfiguracao->api_migracao_conta_url . '/api/v1/' . $tabela . '/' . $id . '?token=' . $orgaoconfiguracao->api_migracao_conta_token;
        }

        return $url;
    }


}
