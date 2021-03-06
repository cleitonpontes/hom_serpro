<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratoItemMinutaEmpenho extends Model
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
    protected static $logName = 'contrato_item_minuta_empenho';

    protected $table = 'contrato_item_minuta_empenho';
//    protected $primaryKey = ['contrato_item_id', 'minutaempenho_id'];
    protected $guarded = [
        //
    ];

    protected $fillable = [
        'contrato_item_id',   // Chave composta: 1/3
        'minutaempenho_id', // Chave composta: 2/3
        'subelemento_id',
        'operacao_id',
        'minutaempenhos_remessa_id',
        'quantidade',
        'valor',
        'numseq'
    ];

    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function gravaContratoItemMinuta($params)
    {
        $this->contrato_item_id = $params['contrato_item_id'];
        $this->minutaempenho_id = $params['minutaempenho_id'];

        $this->save($params);

        return $this->contrato_item_id;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function contrato_item()
    {
        return $this->belongsTo(Contratoitem::class, 'contrato_item_id');
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

    public function getOperacaoDescresAttribute(): string
    {
        return $this->operacao()->first()->descres;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
