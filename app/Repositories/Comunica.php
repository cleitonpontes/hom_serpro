<?php

namespace App\Repositories;

use App\Models\Comunica as Model;
use App\Models\Orgao;
use App\Models\Unidade;
use Spatie\Permission\Models\Role;

class Comunica extends Base
{

    /**
     * Retorna órgãos para exibição e preenchimento de Combo
     *
     * @param int $id
     * @return string
     * @todo Mover para repositório Orgao e, se necessário, criar um método aqui para mero Alias
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getOrgao($id = 0)
    {
        $descOrgao = 'Todas';

        if ($id > 0) {
            $orgao = Orgao::find($id);
            $descOrgao = $orgao->codigo . ' - ' . $orgao->nome;
        }

        return $descOrgao;
    }

    /**
     * Retorna unidades para exibição e preenchimento de Combo
     *
     * @param int $id
     * @return string
     * @todo Mover para repositório Unidade e, se necessário, criar um método aqui para mero Alias
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getUnidade($id = 0)
    {
        $unidadeDesc = 'Todas';

        if ($id > 0) {
            $unidade = Unidade::find($id);
            $unidadeDesc = $unidade->codigo . ' - ' . $unidade->nomeresumido;
        }

        return $unidadeDesc;
    }

    /**
     * Retorna nome do grupo, conforme perfil
     *
     * @param int $id
     * @return string
     * @todo Mover para repositório Grupo e, se necessário, criar um método aqui para mero Alias
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getGrupo($id = 0)
    {
        $grupoDesc = 'Todos';

        if ($id > 0) {
            $grupo = Role::find($id);
            $grupoDesc = $grupo->name;
        }

        return $grupoDesc;
    }

    /**
     * Retorna apenas texto puro do campo mensagem
     *
     * @param string $msg
     * @return string
     * @deprecated Utilizar $this->getTextoPuroDeCampoMensagem($msg)
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getMensagem($msg = '')
    {
        return $this->getTextoPuroDeCampoMensagem($msg);
    }

    /**
     * Retorna descrição da situação ~ ALIAS
     *
     * @param string $sit
     * @return string
     * @deprecated Utilizar $this->getSituacaoComunica()
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacao($sit = '')
    {
        return getSituacaoComunica($sit);
    }

    /**
     * Retorna descrição da situação
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacaoComunica($sit = '')
    {
        $situacao = '';
        $situacoes = $this->getSituacoes();

        if ($sit != '') {
            $situacao = $situacoes[$sit];
        }

        return $situacao;
    }

    /**
     * Retorna array com ids e descrições das situações da comunicação
     *
     * @return array
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacoes()
    {
        $model = new Model();

        return [
            $model::COMUNICA_SITUACAO_ENVIADO => $model::COMUNICA_SITUACAO_ENVIADO_DESC,
            $model::COMUNICA_SITUACAO_INACABADO => $model::COMUNICA_SITUACAO_INACABADO_DESC,
            $model::COMUNICA_SITUACAO_PRONTO_PARA_ENVIO => $model::COMUNICA_SITUACAO_PRONTO_PARA_ENVIO_DESC
        ];
    }

}
