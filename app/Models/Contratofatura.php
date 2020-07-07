<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratofatura extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratofaturas';
    protected static $logFillable = true;
    protected static $logName = 'contratofaturas';

    protected $fillable = [
        'contrato_id',
        'tipolistafatura_id',
        'justificativafatura_id',
        'sfadrao_id',
        'numero',
        'emissao',
        'prazo',
        'vencimento',
        'valor',
        'juros',
        'multa',
        'glosa',
        'valorliquido',
        'processo',
        'protocolo',
        'ateste',
        'repactuacao',
        'infcomplementar',
        'mesref',
        'anoref',
        'situacao'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratoFaturaMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    /**
     * Retorna o órgão da fatura, exibindo código e nome do mesmo
     *
     * @return string
     */
    public function getOrgao()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id', '=', $this->contrato->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;
    }

    /**
     * Retorna a unidade da fatura, exibindo código e nome resumido do mesmo
     *
     * @return string
     */
    public function getUnidade()
    {
        $unidade = Unidade::find($this->contrato->unidade_id);

        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    /**
     * Retorna o tipo da lista
     *
     * @return string
     */
    public function getTipoLista()
    {
        $tipolista = Tipolistafatura::find($this->tipolistafatura_id);

        return $tipolista->nome;
    }

    /**
     * Retorna o número do processo da fatura
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getProcesso()
    {
        return $this->processo;
    }

    /**
     * Retorna o número da fatura
     *
     * @return int
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Retorna a justificativa do processo
     *
     * @return string
     */
    public function getJustificativa()
    {
        if($this->justificativafatura_id){
            $justificativa = Justificativafatura::find($this->justificativafatura_id);
            return $justificativa->nome;
        }

        return '';
    }

    /**
     * Retorna o contrato da fatura
     *
     * @return string
     */
    public function getContrato()
    {
        if ($this->contrato_id) {
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        } else {
            return '';
        }
    }

    /**
     * Retorna o Fornecedor do contrato, exibindo código e nome do mesmo
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->contrato->fornecedor_id);

        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;
    }

    /**
     * Retorna o Tipo de Lista
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getTipoListaFatura()
    {
        // $this->tipolista vem de:
        // public function tipolista()
        // Que, por sua vez, contém $this->belongsTo(...);
        return $this->tipolista->nome;

        // Faz desnecessário essa busca Class:find(...)
        /*
        if ($this->tipolistafatura_id) {
            $tipolistafatura = Tipolistafatura::find($this->tipolistafatura_id);
            return $tipolistafatura->nome;
        } else {
            return '';
        }
        */
    }

    /**
     * Retorna o órgão da fatura, exibindo código e nome do mesmo
     *
     * @return string
     */
    public function getJustificativaFatura()
    {
        if ($this->justificativafatura_id) {
            $justificativafatura = Justificativafatura::find($this->justificativafatura_id);
            return $justificativafatura->nome . ": " . $justificativafatura->descricao;
        } else {
            return '';
        }
    }

    public function getSfpadrao()
    {
        if ($this->sfpadrao_id) {
            $sfpadrao = SfPadrao::find($this->sfpadrao_id);
            return $sfpadrao->anodh . $sfpadrao->codtipodh . str_pad($sfpadrao->numdh, 6, "0", STR_PAD_LEFT);
        } else {
            return '';
        }
    }

    /**
     * Retorna o valor da fatura, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function formatValor()
    {
        return $this->retornaCampoFormatadoComoNumero($this->valor, true);
    }

    /**
     * Retorna o valor dos juros, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function formatJuros()
    {
        return $this->retornaCampoFormatadoComoNumero($this->juros, true);
    }

    /**
     * Retorna o valor da multa, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function formatMulta()
    {
        return $this->retornaCampoFormatadoComoNumero($this->multa, true);
    }

    /**
     * Retorna o valor da glosa, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function formatGlosa()
    {
        $numeroFormatado = $this->retornaCampoFormatadoComoNumero($this->glosa);

        return "(R$ $numeroFormatado)";
    }

    /**
     * Retorna o valor líquido, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function formatValorLiquido()
    {
        return $this->retornaCampoFormatadoComoNumero($this->valorliquido, true);
    }

    /**
     * Retorna a situação, conforme array de situações
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaSituacao()
    {
        $situacoes = config('app.situacao_fatura');
        $situacao = isset($situacoes[$this->situacao]) ? $situacoes[$this->situacao] : '';

        return $situacao;
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
     * Retorna o valor da parcela, formatado como moeda em pt-Br
     *
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function getValorParcela()
    {
        return $this->retornaCampoFormatadoComoNumero($this->contrato->valor_parcela);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function empenhos()
    {
        return $this->belongsToMany(Empenho::class, 'contratofatura_empenhos', 'contratofatura_id', 'empenho_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function tipolista()
    {
        return $this->belongsTo(Tipolistafatura::class, 'tipolistafatura_id');
    }

    public function justificativa()
    {
        return $this->belongsTo(Justificativafatura::class, 'justificativafatura_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
