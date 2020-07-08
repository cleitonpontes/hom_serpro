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

    /**
     * Determina o relacionamento com a Model Contrato
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Anderson Sathler <asathler@gmail.com>
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

    /**
     * Alias para $this->getContratoNumero()
     *
     * @return number
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getContrato()
    {
        return $this->getContratoNumero();
    }

    /**
     * Retorna o Número do contrato
     *
     * @return number
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getContratoNumero()
    {
        return $this->contrato->numero;
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

    /**
     * Retorna a Unidade, exibindo código e nome resumido da mesma
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getUnidade()
    {
        $unidadeCodigo = $this->contrato->unidade->codigo;
        $unidadeNome = $this->contrato->unidade->nomeresumido;

        return $unidadeCodigo . ' - ' . $unidadeNome;
    }

    /**
     * Retorna descrição do Tipo do Contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getTipo()
    {
        return $this->contrato->tipo->descricao;
    }

    /**
     * Retorna descrição da Categoria do Contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getCategoria()
    {
        return $this->contrato->categoria->descricao;
    }

    /**
     * Retorna a descrição da Modalidade do Contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
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
    public function getValorAcumulado()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_acumulado);
    }

    /**
     * Retorna a situação do contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSituacao()
    {
        $situacoes[0] = 'Inativo';
        $situacoes[1] = 'Ativo';

        return $situacoes[$this->contrato->situacao];
    }

    /**
     * Retorna se é Receita ou Despesa
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getReceitaDespesa()
    {
        $retorno['D'] = 'Despesa';
        $retorno['R'] = 'Receita';
        $retorno[''] = '';

        return $retorno[$this->contrato->receita_despesa];
    }

    /**
     * Retorna a descrição da Subcategoria do Contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getSubcategoria()
    {
        return $this->contrato->orgaosubcategoria->descricao;
    }

    /**
     * Retorna o Total de Despesas Acessórias do Contrato
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getTotalDespesasAcessorias()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->total_despesas_acessorias);
    }

    /**
     * Retorna o Órgão, exibindo código e nome do mesmo
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getOrgao()
    {
        $orgaoCodigo = $this->contrato->unidade->orgao->codigo;
        $orgaodNome = $this->contrato->unidade->orgao->nome;

        return $orgaoCodigo . ' - ' . $orgaodNome;
    }

}
