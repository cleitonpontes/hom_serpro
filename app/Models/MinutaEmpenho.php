<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'compra_id',
        'fornecedor_compra_id',
        'fornecedor_empenho_id',
        'saldo_contabil_id',
        'tipo_empenho_id',
        'amparo_legal_id',
        'unidade_id',
        'data_emissao',
        'processo',
        'numero_empenho_sequencial',
        'taxa_cambio',
        'informacao_complementar',
        'local_entrega',
        'descricao',
        'passivo_anterior',
        'conta_contabil_passivo_anterior'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

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
        // return $this->belongsTo(SaldoContabil::class, 'saldo_contabil_id');
    }

    public function tipo_empenho()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_empenho_id');
    }

    public function unidade_id()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
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
