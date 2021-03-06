<?php

namespace App\Models;

use App\Http\Traits\BuscaCodigoItens;
use Backpack\CRUD\CrudTrait;
use Eduardokum\LaravelMailAutoEmbed\Models\EmbeddableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\CompraItemMinutaEmpenho;

class MinutaEmpenho extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use BuscaCodigoItens;

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
        'tipo_empenhopor_id',
        'contrato_id',
        'numero_cipi',
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
        $return = AmparoLegal::select(['amparo_legal.id', DB::raw("ato_normativo ||
                    case when (artigo is not null)  then ' - Artigo: ' || artigo else '' end ||
                    case when (paragrafo is not null)  then ' - Parágrafo: ' || paragrafo else '' end ||
                    case when (amparo_legal.inciso is not null)  then ' - Inciso: ' || amparo_legal.inciso else '' end ||
                    case when (alinea is not null)  then ' - Alinea: ' || alinea else '' end
                    as campo_api_amparo")
        ])->where('minutaempenhos.id', $this->id);

        if ($this->tipo_empenhopor->descricao === 'Contrato') {
            $return->join('contratos', 'contratos.modalidade_id', '=', 'amparo_legal.modalidade_id')
                ->join('minutaempenhos', 'minutaempenhos.contrato_id', '=', 'contratos.id');
            return $return->pluck('campo_api_amparo', 'amparolegal.id')->toArray();
        }

        $return->join('compras', 'compras.modalidade_id', '=', 'amparo_legal.modalidade_id')
            ->join('minutaempenhos', 'minutaempenhos.compra_id', '=', 'compras.id');


        return $return->pluck('campo_api_amparo', 'amparolegal.id')->toArray();
    }

    public function retornaAmparoPorMinutadeContrato()
    {
        return AmparoLegal::select(['amparo_legal.id', DB::raw("ato_normativo ||
                    case when (artigo is not null)  then ' - Artigo: ' || artigo else '' end ||
                    case when (paragrafo is not null)  then ' - Parágrafo: ' || paragrafo else '' end ||
                    case when (amparo_legal.inciso is not null)  then ' - Inciso: ' || amparo_legal.inciso else '' end ||
                    case when (alinea is not null)  then ' - Alinea: ' || alinea else '' end
                    as campo_api_amparo")
        ])->join('contratos', 'contratos.modalidade_id', '=', 'amparo_legal.modalidade_id')
            ->join('minutaempenhos', 'minutaempenhos.contrato_id', '=', 'contratos.id')
            ->where('minutaempenhos.id', $this->id)
            ->pluck('campo_api_amparo', 'amparolegal.id')->toArray();
//        ;dd($teste->getBindings(),$teste->toSql());
    }

    /**
     * Método Necessário para mostrar valor escolhido do campo multiselect após submeter
     * quando o attribute o campo estiver referenciando um alias na consulta da API
     * obrigatório quando utilizar campo select2_from_ajax_multiple_alias
     * @return  string nome_minuta_empenho
     */
    public function retornaConsultaMultiSelect($item)
    {
        $minuta = $this
            ->select(['minutaempenhos.id',
                DB::raw("CONCAT(minutaempenhos.mensagem_siafi, ' - ', to_char(data_emissao, 'DD/MM/YYYY')  )
                                 as nome_minuta_empenho")])
            ->distinct('minutaempenhos.id')
            ->join('compras', 'minutaempenhos.compra_id', '=', 'compras.id')
            ->join('codigoitens', 'codigoitens.id', '=', 'compras.modalidade_id')
            ->join('unidades', 'minutaempenhos.unidade_id', '=', 'unidades.id')
            ->leftJoin('contrato_minuta_empenho_pivot', 'minutaempenhos.id', '=', 'contrato_minuta_empenho_pivot.minuta_empenho_id')
            ->where('minutaempenhos.id', $item->id)
            ->first();

        return $minuta->nome_minuta_empenho;
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

    /**
     * Retorna a situação da remessa recebida
     * @param string $remessa_id
     * @return String
     */
    public function getSituacaoRemessa(string $remessa_id): String
    {
        return $this->remessa()->where('id', $remessa_id)->select('situacao_id')->first()->situacao->descricao;
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

    public function getTipoEmpenhoPor()
    {
        return $this->tipo_empenhopor->descricao ?? '';
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

    public function getItens($minutaempenhos_remessa_id)
    {
        $tipo_contrato_id = $this->retornaIdCodigoItem('Tipo Empenho Por', 'Contrato');
        clock($tipo_contrato_id);

        //SE FOR CONTRATO
        if ($this->tipo_empenhopor_id == $tipo_contrato_id) {
            return $this->contratoItemMinutaEmpenho()
                ->join(
                    'naturezasubitem',
                    'naturezasubitem.id',
                    '=',
                    'contrato_item_minuta_empenho.subelemento_id'
                )
                ->join(
                    'codigoitens',
                    'codigoitens.id',
                    '=',
                    'contrato_item_minuta_empenho.operacao_id'
                )
                ->join(
                    'contratoitens',
                    'contratoitens.id',
                    '=',
                    'contrato_item_minuta_empenho.contrato_item_id'
                )
                ->where('contrato_item_minuta_empenho.minutaempenhos_remessa_id', $minutaempenhos_remessa_id)
                ->select(
                    DB::raw('contratoitens.numero_item_compra              AS "numeroItemCompra"'),
                    DB::raw('contrato_item_minuta_empenho.numseq                 AS "numeroItemEmpenho"'),
                    DB::raw('CEIL(contrato_item_minuta_empenho.quantidade)             AS "quantidadeEmpenhada"'),
                    DB::raw('naturezasubitem.codigo AS subelemento'),
                    DB::raw('LEFT(codigoitens.descres, 1)     AS "tipoEmpenhoOperacao"'),
                    DB::raw('contratoitens.valorunitario AS "valorUnitarioItem"'),
                    DB::raw('NULL AS "tipoUASG"'),
                    DB::raw('(SELECT SUM(valor)
                        FROM contrato_item_minuta_empenho cime
                        WHERE cime.minutaempenho_id = contrato_item_minuta_empenho.minutaempenho_id
                          AND cime.minutaempenhos_remessa_id =
                              contrato_item_minuta_empenho.minutaempenhos_remessa_id) AS "valorTotalEmpenho"
                    ')
                )
                ->get();
        }

        return
            $this->compraItemMinutaEmpenho()
                ->join(
                    'naturezasubitem',
                    'naturezasubitem.id',
                    '=',
                    'compra_item_minuta_empenho.subelemento_id'
                )
                ->join(
                    'codigoitens',
                    'codigoitens.id',
                    '=',
                    'compra_item_minuta_empenho.operacao_id'
                )
                ->join(
                    'compra_items',
                    'compra_items.id',
                    '=',
                    'compra_item_minuta_empenho.compra_item_id'
                )
                ->join(
                    'compra_item_fornecedor',
                    'compra_item_fornecedor.compra_item_id',
                    '=',
                    'compra_items.id'
                )
                ->join(
                    'compra_item_unidade',
                    'compra_item_unidade.compra_item_id',
                    '=',
                    'compra_items.id'
                )
                ->where('compra_item_minuta_empenho.minutaempenhos_remessa_id', $minutaempenhos_remessa_id)
                ->select(
                    DB::raw('compra_items.numero              AS "numeroItemCompra"'),
                    DB::raw('compra_item_minuta_empenho.numseq                 AS "numeroItemEmpenho"'),
                    DB::raw('CEIL(compra_item_minuta_empenho.quantidade)             AS "quantidadeEmpenhada"'),
                    DB::raw('naturezasubitem.codigo AS subelemento'),
                    DB::raw('LEFT(codigoitens.descres, 1)     AS "tipoEmpenhoOperacao"'),
                    DB::raw('compra_item_fornecedor.valor_unitario AS "valorUnitarioItem"'),
                    DB::raw('compra_item_unidade.tipo_uasg AS "tipoUASG"'),
                    DB::raw('(SELECT SUM(valor)
                        FROM compra_item_minuta_empenho cime
                        WHERE cime.minutaempenho_id = compra_item_minuta_empenho.minutaempenho_id
                          AND cime.minutaempenhos_remessa_id =
                              compra_item_minuta_empenho.minutaempenhos_remessa_id) AS "valorTotalEmpenho"
                    ')
                )
                ->get();
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

    public function tipo_empenhopor()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_empenhopor_id');
    }

    public function unidade_id()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function passivo_anterior()
    {
        return $this->hasMany(ContaCorrentePassivoAnterior::class, 'minutaempenho_id');
    }

    public function remessa()
    {
        return $this->hasMany(MinutaEmpenhoRemessa::class, 'minutaempenho_id');
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

    public function compraItemMinutaEmpenho()
    {
        return $this->hasMany(CompraItemMinutaEmpenho::class, 'minutaempenho_id');
    }

    public function contratoItemMinutaEmpenho()
    {
        return $this->hasMany(ContratoItemMinutaEmpenho::class, 'minutaempenho_id');
    }

    public function contrato_vinculado()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
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

    public function getMaxRemessaAttribute()
    {
        return $this->remessa()->max('id');
    }

    public function getSituacaoDescricaoAttribute()
    {
        return $this->situacao()->first()->descricao;
    }

    public function getEmpenhoPorAttribute()
    {
        return $this->tipo_empenhopor->descricao;
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
