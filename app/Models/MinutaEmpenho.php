<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Eduardokum\LaravelMailAutoEmbed\Models\EmbeddableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class MinutaEmpenho extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    protected static $logName = 'minuta_empenhos';

    protected $table = 'minutaempenhos';

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'amparo_legal_id',
        'compra_id',
        'conta_contabil_passivo_anterior',
        'data_emissao',
        'descricao',
        'etapa',
        'fornecedor_compra_id',
        'fornecedor_empenho_id',
        'informacao_complementar',
        'local_entrega',
        'numero_empenho_sequencial',
        'passivo_anterior',
        'processo',
        'saldo_contabil_id',
        'situacao_id',
        'taxa_cambio',
        'tipo_empenho_id',
        'tipo_minuta_empenho',
        'unidade_id',
        'valor_total',
        'empenhocontrato'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /**
     * Retorna dados da Minuta de Empenho para apresentação
     *
     * @return array
     */
    public function retornaListagem()
    {
        $ug = session('user_ug');
        $listagem = MinutaEmpenho::where('id', 1)->get();

//        $listagem->select([
//            'apropriacoes.id',
//            'competencia',
//            'nivel',
//            'valor_liquido',
//            'valor_bruto',
//            'fase_id',
//            'F.fase',
//            'arquivos'
//        ])->where('ug', $ug);

        return $listagem;
    }

    public function retornaAmparoPorMinuta()
    {
        return AmparoLegal::select(['amparo_legal.id', DB::raw("ato_normativo ||
                    case when (artigo is not null)  then ' - Artigo: ' || artigo else '' end ||
                    case when (paragrafo is not null)  then ' - Parágrafo: ' || paragrafo else '' end ||
                    case when (amparo_legal.inciso is not null)  then ' - Inciso: ' || amparo_legal.inciso else '' end ||
                    case when (alinea is not null)  then ' - Alinea: ' || alinea else '' end
                    as campo_api_amparo")
        ])->join('compras', 'compras.modalidade_id', '=', 'amparo_legal.modalidade_id')
            ->join('minutaempenhos', 'minutaempenhos.compra_id', '=', 'compras.id')
            ->where('minutaempenhos.id', $this->id)->pluck('campo_api_amparo', 'amparolegal.id')->toArray();
    }

    public function retornaConsultaMultiSelect($item)
    {
     $minuta =  $this->select(['minutaempenhos.id as id_minuta',
        DB::raw("CONCAT(unidades.codigosiasg,'  ',codigoitens.descres, '  ' ,compras.numero_ano, ' - ',
                                    minutaempenhos.numero_empenho_sequencial, ' - ', to_char(data_emissao, 'DD/MM/YYYY')  )
                             as nome_minuta_empenho")])->distinct()
        ->join('compras', 'minutaempenhos.compra_id', '=', 'compras.id')
        ->join('codigoitens' ,'codigoitens.id', '=',  'compras.modalidade_id')
        ->join('unidades', 'minutaempenhos.unidade_id', '=', 'unidades.id')
        ->leftJoin('contrato_minuta_empenho_pivot', 'minutaempenhos.id', '=', 'contrato_minuta_empenho_pivot.minuta_empenho_id')
         ->where('minutaempenhos.id', $item->id)
         ->first();

         return  $minuta->nome_minuta_empenho;

    }

    public function atualizaFornecedorCompra($fornecedor_id)
    {
        $this->fornecedor_compra_id = $fornecedor_id;
        $this->fornecedor_empenho_id = $fornecedor_id;
        $this->etapa = 3;
        $this->update();
    }

    public function getUnidade()
    {

        $unidade = $this->unidade_id()->first();
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getUnidadeCompra()
    {
        $unidade = $this->compra->unidade_origem()->first();
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getSituacao()
    {
        return $this->situacao->descricao;
    }

    public function getFornecedorEmpenho()
    {
        $fornecedor = $this->fornecedor_empenho()->first();
        if ($fornecedor) {
            return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;
        }
        return '';
    }

    /**
     * Retorna descrição do Tipo do Empenho
     *
     * @return string
     */
    public function getTipoEmpenho()
    {
        return $this->tipo_empenho->descricao ?? '';
    }

    /**
     * Retorna descrição do Amparo Legal
     *
     * @return string
     */
    public function getAmparoLegal()
    {

        if (isset($this->amparo_legal)) {
            $artigo = isset($this->amparo_legal->artigo) ? ' - Artigo: ' . $this->amparo_legal->artigo : '';
            $paragrafo = isset($this->amparo_legal->paragrafo) ? ' - Parágrafo: ' . $this->amparo_legal->paragrafo : '';
            $inciso = isset($this->amparo_legal->inciso) ? ' - Inciso: ' . $this->amparo_legal->inciso : '';
            $alinea = isset($this->amparo_legal->alinea) ? ' - Alínea: ' . $this->amparo_legal->alinea : '';

            return $this->amparo_legal->ato_normativo . $artigo . $paragrafo . $inciso . $alinea;
        }
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function amparo_legal()
    {
        return $this->belongsTo(AmparoLegal::class, 'amparo_legal_id');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    public function empenho_dados()
    {
        return $this->hasMany(SfOrcEmpenhoDados::class, 'minutaempenho_id');
    }

    public function fornecedor_compra()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_compra_id');
    }

    public function fornecedor_empenho()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_empenho_id');
    }

    public function saldo_contabil()
    {
        return $this->belongsTo(SaldoContabil::class, 'saldo_contabil_id');
    }

    public function tipo_empenho()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_empenho_id');
    }

    public function unidade_id()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function passivo_anterior()
    {
        return $this->hasMany(ContaCorrentePassivoAnterior::class, 'minutaempenho_id');
    }

    public function situacao()
    {
        return $this->belongsTo(Codigoitem::class, 'situacao_id');
    }

    public function contrato()
    {
        return $this->belongsToMany(
            'App\Models\Contrato',
            'contrato_minuta_empenho_pivot',
            'minuta_empenho_id',
            'contrato_id'
        );
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

    public function getCompraModalidadeAttribute()
    {
        $compra = $this->compra()->first()->modalidade()->first();
        return $compra->descres . ' - ' . $compra->descricao;
    }

    public function getTipoCompraAttribute()
    {
        return $this->compra()->first()->tipo_compra()->first()->descricao;
    }

    public function getNumeroAnoAttribute()
    {
        return $this->compra()->first()->numero_ano;
    }

    public function getIncisoAttribute()
    {
        return $this->compra()->first()->inciso;
    }

    public function getLeiAttribute()
    {
        return $this->compra()->first()->lei;
    }

    public function getSituacaoDescricaoAttribute()
    {
        return $this->situacao()->first()->descricao;
    }

    public function getFornecedorEmpenhoCpfcnpjidgenerSessaoAttribute()
    {
        $fornecedor = $this->fornecedor_empenho()->first();
        return $fornecedor->cpf_cnpj_idgener ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
