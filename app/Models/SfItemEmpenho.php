<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SfItemEmpenho extends Model
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
    protected static $logName = 'sfitemempenho';

    protected $table = 'sfitemempenho';

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'sforcempenhodado_id',
        'numseqitem',
        'codsubelemento',
        'descricao'
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

    public function sforcempenhodados()
    {
        return $this->belongsTo(SfOrcEmpenhoDados::class, 'sforcempenhodado_id');
    }

    public function operacao_item_empenho()
    {
        return $this->hasOne(SfOperacaoItemEmpenho::class, 'sfitemempenho_id');
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
