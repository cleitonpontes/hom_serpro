<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
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

    public function getOrgao()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id', '=', $this->contrato->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;
    }

    public function getUnidade()
    {
        $unidade = Unidade::find($this->contrato->unidade_id);

        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getTipoLista()
    {
        $tipolista = Tipolistafatura::find($this->tipolistafatura_id);

        return $tipolista->nome;
    }

    public function getProcesso()
    {
        return $this->processo;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getJustificativa()
    {
        if($this->justificativafatura_id){
            $justificativa = Justificativafatura::find($this->justificativafatura_id);
            return $justificativa->nome;
        }

        return '';
    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->contrato->fornecedor_id);

        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;
    }

    public function getContrato()
    {
        if ($this->contrato_id) {
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        } else {
            return '';
        }
    }

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

    public function formatValor()
    {
        return $this->retornaCampoFormatadoComoNumero($this->valor, true);
    }

    public function formatJuros()
    {
        return $this->retornaCampoFormatadoComoNumero($this->juros, true);
    }

    public function formatMulta()
    {
        return $this->retornaCampoFormatadoComoNumero($this->multa, true);
    }

    public function formatGlosa()
    {
        $numeroFormatado = $this->retornaCampoFormatadoComoNumero($this->glosa);

        return "(R$ $numeroFormatado)";
    }

    public function formatValorLiquido()
    {
        if ($this->valorliquido) {
            return 'R$ ' . number_format($this->valorliquido, 2, ',', '.');
        } else {
            return '';
        }

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
    public function usuario()
    {
        return $this->belongsTo(BackpackUser::class, 'user_id');
    }
    */

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
