<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CompraItemMinutaEmpenho extends Model
{
    use CrudTrait;
    use LogsActivity;

//    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    protected static $logName = 'compra_item_minuta_empenho';

    protected $table = 'compra_item_minuta_empenho';
    protected $guarded = [
        //
    ];

    protected $fillable = [
        'compra_item_id',   // Chave composta: 1/3
        'minutaempenho_id', // Chave composta: 2/3
        'subelemento_id',
        'operacao_id',
        'remessa', // Chave composta: 3/3
        'quantidade',
        'valor',
        'minutaempenhos_remessa_id'
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

    public function compra_item()
    {
        return $this->belongsTo(CompraItem::class, 'compra_item_id');
    }

    public function minutaempenho()
    {
        return $this->belongsTo(MinutaEmpenho::class, 'minutaempenho_id');
    }

    public function subelemento()
    {
        return $this->belongsTo(Naturezasubitem::class, 'subelemento_id');
    }

    public function minutaempenhos_remessa()
    {
        return $this->belongsTo(MinutaEmpenhoRemessa::class, 'minutaempenhos_remessa_id');
    }

    public function operacao()
    {
        return $this->belongsTo(Codigoitem::class, 'operacao_id');
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

    public function getSituacaoRemessaAttribute(): string
    {
        return $this->minutaempenhos_remessa->situacao->descricao;
    }

    public function getOperacaoAttribute(): string
    {
        return $this->operacao()->first()->descricao;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
