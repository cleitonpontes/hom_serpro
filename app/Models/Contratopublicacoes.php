<?php

namespace App\Models;

use App\Http\Traits\Formatador;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratoPublicacoes extends ContratoBase
{
    use CrudTrait;
    use LogsActivity;
    use Formatador;

    protected static $logFillable = true;
    protected static $logName = 'contratopublicacoes';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratopublicacoes';
    protected $fillable = [
        'contratohistorico_id',
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
    public function contratohistorico()
    {
        return $this->belongsTo(Contratohistorico::class, 'contratohistorico_id');
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
