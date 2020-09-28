<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratoPublicacoes extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'contratopublicacoes';
    public $timestamps = false;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratopublicacoes';
    protected $fillable = [
        'contrato_id',
        'data_publicacao',
        'texto_rtf',
        'hash',
        'status',
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
