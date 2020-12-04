<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AmparoLegalRestricao extends Model
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
    protected static $logName = 'amparo_legal_restricoes';

    protected $table = 'amparo_legal_restricoes';

    protected $fillable = [
        'amparo_legal_id',
        'tipo_restricao_id',
        'codigo_restricao'
    ];


    public function amparosLegais()
    {
        return $this->belongsTo(AmparoLegal::class, 'amparo_legal_id');
    }

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


}
