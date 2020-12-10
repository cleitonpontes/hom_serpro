<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SfOrcEmpenhoDados extends Model
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
    protected static $logName = 'sforcempenhodados';

    protected $table = 'sforcempenhodados';

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'minutaempenho_id',
        'ugemitente',
        'anoempenho',
        'tipoempenho',
        'numempenho',
        'dtemis',
        'txtprocesso',
        'vlrtaxacambio',
        'vlrempenho',
        'codfavorecido',
        'codamparolegal',
        'txtinfocompl',
        'codtipotransf',
        'txtlocalentrega',
        'txtdescricao',
        'numro', // numero??
        'mensagemretorno',
        'situacao'
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

    public function minuta_empenhos()
    {
        return $this->belongsTo(MinutaEmpenho::class, 'minutaempenho_id');
    }

    public function passivos_anteriores()
    {
        return $this->hasMany(SfPassivoAnterior::class, 'sforcempenhodado_id');
    }

    public function itens_empenho()
    {
        return $this->hasMany(SfItemEmpenho::class, 'sforcempenhodado_id');
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
