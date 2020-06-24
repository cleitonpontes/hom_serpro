<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Illuminate\Database\Eloquent\Model;

class ContratoBase extends Model
{

    use Formatador;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getContratoNumero()
    {
        return $this->getContrato();
    }

    public function getContrato()
    {
        return $this->contrato->numero;

        /*
        if ($this->contrato_id) {
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        } else {
            return '';
        }
        */
    }

    /**
     * Retorna o Fornecedor, exibindo código e nome do mesmo
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getFornecedor()
    {
        $fornecedorCpfCnpj = $this->contrato->fornecedor->cpf_cnpj_idgener;
        $fornecedorNome = $this->contrato->fornecedor->nome;

        return $fornecedorCpfCnpj . ' - ' . $fornecedorNome;
    }

    public function getUnidade()
    {
        $unidadeCodigo = $this->contrato->unidade->codigo;
        $unidadeNome = $this->contrato->unidade->nomeresumido;

        return $unidadeCodigo . ' - ' . $unidadeNome;
    }

    public function getTipo()
    {
        return $this->contrato->tipodescricao;
    }

    public function getCategoria()
    {
        return $this->contrato->categoria->descricao;
    }

    public function getModalidade()
    {
        return $this->contrato->modalidade->descricao;
    }

    /**
     * Retorna a Data de Assinatura
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getDataAssinatura()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->data_assinatura);
    }

    /**
     * Retorna a Data de Publicação
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getDataPublicacao()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->data_publicacao);
    }

    /**
     * Retorna a Data de Início da Vigência
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getVigenciaInicio()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_inicio);
    }

    /**
     * Retorna a Data de Término da Vigência
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getVigenciaFim()
    {
        return $this->retornaDataAPartirDeCampo($this->contrato->vigencia_fim);
    }

    /**
     * Retorna o valor inicial, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getValorInicial()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_inicial);
    }

    /**
     * Retorna o valor global, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getValorGlobal()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_global);
    }

    /**
     * Retorna o número de parcelas, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getNumeroParcelas()
    {
        return $this->contrato->num_parcelas;
    }

    /**
     * Retorna o valor da parcela, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getValorParcela()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_parcela);
    }

    /**
     * Retorna o valor acumulado, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getValorAcumuolado()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_acumulado);
    }

    /**
     * Retorna a situação do contrato
     *
     * @return mixed
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacao()
    {
        $situacoes[0] = 'Inativo';
        $situacoes[1] = 'Ativo';

        return $situacoes[$this->contrato->situacao];
    }

    public function getReceitaDespesa()
    {
        $retorno['D'] = 'Despesa';
        $retorno['R'] = 'Receita';
        $retorno[''] = '';

        return $retorno[$this->receita_despesa];
    }

    public function getSubcategoria()
    {
        return $this->contrato->orgaosubcategoria->descricao;
    }

    public function getTotalDespesasAcessorias()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->total_despesas_acessorias);
    }

    public function getOrgao()
    {
        $orgaoCodigo = $this->contrato->unidade->orgao->codigo;
        $orgaodNome = $this->contrato->unidade->orgao->nome;

        return $orgaoCodigo . ' - ' . $orgaodNome;
    }

}
