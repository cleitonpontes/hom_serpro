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
    // protected $primaryKey = 'id';


    // use CrudTrait;
    // use LogsActivity;
    // use SoftDeletes;




    protected $fillable = [
        'amparo_legal_id',
        'tipo_restricao_id',
        'codigo_restricao'
    ];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    public function amparolegal()
    {
        return $this->belongsTo(AmparoLegal::class, 'amparo_legal_id');
    }
    public function tiporestricao()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_restricao_id');
    }



    // public function getAmparoLegal()
    // {
    //     if ($this->amparo_legal_id) {
    //         $amparo_legal = AmparoLegal::find($this->amparo_legal_id);
    //         return $amparo_legal->codigo;
    //     }
    //     return '';
    // }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    // public function amparo_legal()
    // {
    //     return $this->belongsTo(AmparoLegal::class, 'amparo_legal_id');
    // }

}
