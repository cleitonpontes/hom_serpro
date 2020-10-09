<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratohistorico extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    // use SoftDeletes;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'contrato_historico';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratohistorico';
    protected $fillable = [
        'numero',
        'contrato_id',
        'fornecedor_id',
        'unidade_id',
        'unidadeorigem_id',
        'tipo_id',
        'categoria_id',
        'subcategoria_id',
        'receita_despesa',
        'processo',
        'objeto',
        'info_complementar',
        'fundamento_legal',
        'amparo_legal_id',
        'modalidade_id',
        'licitacao_numero',
        'data_assinatura',
        'data_publicacao',
        'vigencia_inicio',
        'vigencia_fim',
        'valor_inicial',
        'valor_global',
        'num_parcelas',
        'valor_parcela',
        'novo_valor_global',
        'novo_num_parcelas',
        'novo_valor_parcela',
        'data_inicio_novo_valor',
        'observacao',
        'retroativo',
        'retroativo_mesref_de',
        'retroativo_anoref_de',
        'retroativo_mesref_ate',
        'retroativo_anoref_ate',
        'retroativo_vencimento',
        'retroativo_valor',
        'retroativo_soma_subtrai',
        'unidades_requisitantes',
        'situacao',
        'supressao',
        'unidadecompra_id',
        'publicado'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function inserirContratohistoricoMigracaoConta(array $dados)
    {
        $this->fill($dados);
        $this->save();

        return $this;
    }

    public function createNewHistorico(array $dado)
    {
        $contratohistorico = $this->where('numero', '=', $dado['numero'])
            ->where('contrato_id', '=', $dado['contrato_id'])
            ->where('tipo_id', '=', $dado['tipo_id'])
            ->first();

        if (!$contratohistorico) {
            $this->fill($dado);
            $this->save();
            return $this;
        }

        $contratohistorico->fill($dado);
        $contratohistorico->save();

        return $contratohistorico;
    }

    public function getReceitaDespesaHistorico()
    {
        $retorno['D'] = 'Despesa';
        $retorno['R'] = 'Receita';
        $retorno[''] = '';

        return $retorno[$this->receita_despesa];
    }

    public function getTipo()
    {
        return $this->tipo->descricao;
    }

    public function getCategoria()
    {
        return isset($this->categoria->descricao) ? $this->categoria->descricao : '';
    }

    public function getSubCategoria()
    {
        return isset($this->orgaosubcategoria) ? $this->orgaosubcategoria->descricao : '';
    }

    public function getFornecedorHistorico()
    {
        $fornecedorCpfCnpj = $this->fornecedor->cpf_cnpj_idgener;
        $fornecedorNome = $this->fornecedor->nome;

        return $fornecedorCpfCnpj . ' - ' . $fornecedorNome;
    }

    public function getModalidade()
    {
        return isset($this->modalidade) ? $this->modalidade->descricao : '';
    }

    public function formatVlrGlobalHistorico()
    {
        return $this->retornaCampoFormatadoComoNumero($this->valor_global, true);
    }

    public function formatVlrParcelaHistorico()
    {
        return $this->retornaCampoFormatadoComoNumero($this->valor_parcela, true);
    }

    // NOTA: Demais formatadores não estavam presentes, numa revisão preliminar, na ContratohistoricoCrudController,
    //       contudo, foram mantidas pela eventual manutenção de retrocompatibilidade.
    //
    // Métodos com alterações
    public function formatNovoVlrGlobalHistorico()
    {
        return $this->retornaCampoFormatadoComoNumero($this->novo_valor_global, true);
    }

    public function formatNovoVlrParcelaHistorico()
    {
        return $this->retornaCampoFormatadoComoNumero($this->novo_valor_parcela, true);
    }

    public function formatVlrRetroativoValor()
    {
        return $this->retornaCampoFormatadoComoNumero($this->retroativo_valor, true);

        /*
        if ($this->retroativo_valor) {
            return 'R$ ' . number_format($this->retroativo_valor, 2, ',', '.');
        }
        return '';
        */
    }

    public function formatVlrGlobal()
    {
        return $this->retornaCampoFormatadoComoNumero($this->valor_global, true);
    }

    public function formatVlrParcela()
    {
        return $this->retornaCampoFormatadoComoNumero($this->valor_parcela, true);
    }

    //
    // Métodos sem NENHUMA alteração!
    public function getUnidadeHistorico()
    {
        $unidade = Unidade::find($this->unidade_id);

        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getUnidadeOrigemHistorico()
    {
        if(!isset($this->unidadeorigem_id)){
            return '';
        }

        return $this->unidadeorigem->codigo . ' - ' . $this->unidadeorigem->nomeresumido;
    }

    public function getOrgaoHistorico()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id', '=', $this->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;
    }

    public function getTipoHistorico()
    {
        if ($this->tipo_id) {
            $tipo = Codigoitem::find($this->tipo_id);

            return $tipo->descricao;
        } else {
            return '';
        }
    }

    public function getCategoriaHistorico()
    {
        if (!$this->categoria_id) {
            return '';
        }

        $categoria = Codigoitem::find($this->categoria_id);

        return $categoria->descricao;
    }

    public function getSubCategoriaHistorico()
    {
        if ($this->subcategoria_id) {
            $subcategoria = OrgaoSubcategoria::find($this->subcategoria_id);
            return $subcategoria->descricao;
        } else {
            return '';
        }
    }

    public function getRetroativoMesAnoReferenciaDe()
    {
        if ($this->retroativo_mesref_de and $this->retroativo_anoref_de) {
            return $this->retroativo_mesref_de . '/' . $this->retroativo_anoref_de;
        }

        return '';
    }

    public function getRetroativoMesAnoReferenciaAte()
    {
        if ($this->retroativo_mesref_ate and $this->retroativo_anoref_ate) {
            return $this->retroativo_mesref_ate . '/' . $this->retroativo_anoref_ate;
        }

        return '';
    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);

        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;
    }

    public function getReceitaDespesa()
    {
        if ($this->receita_despesa == 'D') {
            return 'Despesa';
        }
        if ($this->receita_despesa == 'R') {
            return 'Receita';
        }

        return '';
    }

    public function getUnidade()
    {
        $unidade = Unidade::find($this->unidade_id);

        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getUnidadeOrigem()
    {
        if(!isset($this->unidadeorigem_id)){
            return '';
        }

        return $this->unidadeorigem->codigo . ' - ' . $this->unidadeorigem->nomeresumido;
    }

    public function getOrgao()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id', '=', $this->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;
    }

    public function retornaAmparo()
    {
        $amparo = "";

        $cont = (is_array($this->amparolegal)) ? count($this->amparolegal) : 0;

        foreach ($this->amparolegal as $key => $value){

            if($cont < 2){
                $amparo .= $value->ato_normativo;
                $amparo .= (!is_null($value->artigo)) ? " - Artigo: ".$value->artigo : "";
                $amparo .= (!is_null($value->paragrafo)) ? " - Parágrafo: ".$value->paragrafo : "";
                $amparo .= (!is_null($value->inciso)) ? " - Inciso: ".$value->inciso : "";
                $amparo .= (!is_null($value->alinea)) ? " - Alinea: ".$value->alinea : "";
            }
            if($key == 0 && $cont > 1){
                $amparo .= $value->ato_normativo;
                $amparo .= (!is_null($value->artigo)) ? " - Artigo: ".$value->artigo : "";
                $amparo .= (!is_null($value->paragrafo)) ? " - Parágrafo: ".$value->paragrafo : "";
                $amparo .= (!is_null($value->inciso)) ? " - Inciso: ".$value->inciso : "";
                $amparo .= (!is_null($value->alinea)) ? " - Alinea: ".$value->alinea : "";
            }
            if($key > 0 && $key < ($cont - 1)){
                $amparo .= ", ".$value->ato_normativo;
                $amparo .= (!is_null($value->artigo)) ? " - Artigo: ".$value->artigo : "";
                $amparo .= (!is_null($value->paragrafo)) ? " - Parágrafo: ".$value->paragrafo : "";
                $amparo .= (!is_null($value->inciso)) ? " - Inciso: ".$value->inciso : "";
                $amparo .= (!is_null($value->alinea)) ? " - Alinea: ".$value->alinea : "";
            }
            if($key == ($cont - 1)){
                $amparo .= " e ".$value->ato_normativo;
                $amparo .= (!is_null($value->artigo)) ? " - Artigo: ".$value->artigo : "";
                $amparo .= (!is_null($value->paragrafo)) ? " - Parágrafo: ".$value->paragrafo : "";
                $amparo .= (!is_null($value->inciso)) ? " - Inciso: ".$value->inciso : "";
                $amparo .= (!is_null($value->alinea)) ? " - Alinea: ".$value->alinea : "";
            }
        }

        return $amparo;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    // Método contrato() passa a ser herdado de ContratoBase
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
    */

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function cronograma()
    {
        return $this->hasMany(Contratocronograma::class, 'contratohistorico_id');
    }

    public function unidadeorigem()
    {
        return $this->belongsTo(Unidade::class, 'unidadeorigem_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function orgaosubcategoria()
    {
        return $this->belongsTo(OrgaoSubcategoria::class, 'subcategoria_id');
    }

    public function saldosItens()
    {
        return $this->morphMany(Saldohistoricoitem::class, 'saldoable');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function unidadecompra()
    {
        return $this->belongsTo(Unidade::class, 'unidadecompra_id');
    }


    public function publicacao()
    {
        return $this->hasOne(Contratopublicacoes::class, 'contratohistorico_id');
    }

    public function amparolegal()
    {
        return $this->belongsToMany(
            'App\Models\AmparoLegal',
            'amparo_legal_contratohistorico',
            'contratohistorico_id',
            'amparo_legal_id'
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

}
