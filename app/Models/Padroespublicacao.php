<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Padroespublicacao extends Model
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'padroespublicacao';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'padroespublicacao';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'tipo_contrato_id',
        'tipo_mudanca_id',
        'texto_padrao',
        'identificador_norma_id',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTipoContrato()
    {
        $codigo = Codigoitem::find($this->tipo_contrato_id);

        return $codigo->descricao;
    }
    public function getTipoMudanca()
    {
        $codigo = Codigoitem::find($this->tipo_mudanca_id);

        return $codigo->descricao;
    }
    public function getIdentificadorNorma()
    {
        $codigo = Codigoitem::find($this->identificador_norma_id);

        return $codigo->descricao;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function tipoContrato()
    {
        $this->belongsTo(Codigoitem::class, 'tipo_contrato_id');
    }

    public function tipoMudanca()
    {
        $this->belongsTo(Codigoitem::class, 'tipo_mudanca_id');
    }

    public function identificadorNorma()
    {
        $this->belongsTo(Codigoitem::class, 'identificador_norma_id');
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
