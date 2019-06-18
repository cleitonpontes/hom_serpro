<?php

namespace App\Models;

use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contrato extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contrato';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'numero',
        'fornecedor_id',
        'unidade_id',
        'tipo_id',
        'categoria_id',
        'processo',
        'objeto',
        'info_complementar',
        'receita_despesa',
        'fundamento_legal',
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
        'valor_acumulado',
        'situacao_siasg',
        'situacao',
    ];


    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function atualizaContratoFromHistorico(Contratohistorico $contratohistorico){



    }

    public function getFornecedor()
    {
        $fornecedor = Fornecedor::find($this->fornecedor_id);
        return $fornecedor->cpf_cnpj_idgener . ' - ' . $fornecedor->nome;

    }

    public function getReceitaDespesa()
    {
        if($this->receita_despesa == 'D'){
            return 'Despesa';
        }
        if($this->receita_despesa == 'R'){
            return 'Receita';
        }

        return '';
    }

    public function getUnidade()
    {
        $unidade = Unidade::find($this->unidade_id);
        return $unidade->codigo . ' - ' . $unidade->nomeresumido;

    }

    public function getOrgao()
    {
        $orgao = Orgao::whereHas('unidades', function ($query) {
            $query->where('id','=', $this->unidade_id);
        })->first();

        return $orgao->codigo . ' - ' . $orgao->nome;

    }

    public function getTipo()
    {
        if($this->tipo_id){
            $tipo = Codigoitem::find($this->tipo_id);

            return $tipo->descricao;
        }else{
            return '';
        }


    }


    public function getCategoria()
    {
        $categoria = Codigoitem::find($this->categoria_id);

        return $categoria->descricao;

    }


    public function formatVlrParcela()
    {
        return 'R$ '.number_format($this->valor_parcela, 2, ',', '.');
    }

    public function formatVlrGlobal()
    {
        return 'R$ '.number_format($this->valor_global, 2, ',', '.');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function historico(){

        return $this->hasMany(Contratohistorico::class, 'contrato_id');

    }

    public function cronograma(){

        return $this->hasMany(Contratocronograma::class, 'contrato_id');

    }


    public function responsaveis(){

        return $this->hasMany(Contratoresponsavel::class, 'contrato_id');

    }

    public function garantias(){

        return $this->hasMany(Contratogarantia::class, 'contrato_id');

    }

    public function arquivos(){

        return $this->hasMany(Contratoarquivo::class, 'contrato_id');

    }

    public function empenhos(){

        return $this->hasMany(Contratoempenho::class, 'contrato_id');

    }

    public function faturas(){

        return $this->hasMany(Contratofatura::class, 'contrato_id');

    }

    public function ocorrencias(){

        return $this->hasMany(Contratoocorrencia::class, 'contrato_id');

    }

    public function terceirizados(){

        return $this->hasMany(Contratoterceirizado::class, 'contrato_id');

    }

    public function unidade()
    {

        return $this->belongsTo(Unidade::class, 'unidade_id');

    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Codigoitem::class, 'categoria_id');
    }

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
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
