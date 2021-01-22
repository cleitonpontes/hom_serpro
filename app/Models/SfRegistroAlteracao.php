<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SfRegistroAlteracao extends Model
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
    protected static $logName = 'sfregistroalteracao';

    protected $table = 'sfregistroalteracao';

    protected $fillable = [
        'sforcempenhodado_id',
        'dtemeis',
        'txtmotivo',
        'indrindispcaixa'
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
