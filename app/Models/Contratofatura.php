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

    protected static $logFillable = true;
    protected static $logName = 'contratofaturas';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratofaturas';
    // protected $primaryKey = 'id';
    // protected $guarded = ['id'];
    // protected $hidden = [];
    // protected $dates = [];
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
    // public $timestamps = false;

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
        if ($this->tipolistafatura_id) {
            $tipolistafatura = Tipolistafatura::find($this->tipolistafatura_id);
            return $tipolistafatura->nome;
        } else {
            return '';
        }
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
        if ($this->valor) {
            return 'R$ ' . number_format($this->valor, 2, ',', '.');
        } else {
            return '';
        }

    }

    public function formatJuros()
    {
        if ($this->juros) {
            return 'R$ ' . number_format($this->juros, 2, ',', '.');
        } else {
            return '';
        }

    }

    public function formatMulta()
    {
        if ($this->multa) {
            return 'R$ ' . number_format($this->multa, 2, ',', '.');
        } else {
            return '';
        }

    }

    public function formatGlosa()
    {
        if ($this->glosa) {
            return '(R$ ' . number_format($this->glosa, 2, ',', '.') . ')';
        } else {
            return '';
        }

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
