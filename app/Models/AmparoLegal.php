<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AmparoLegal extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'amparo_legal';

    protected $table = 'amparo_legal';

    protected $fillable = [
        'codigo',
        'modalidade_id',
        'ato_normativo',
        'artigo',
        'paragrafo',
        'inciso',
        'alinea'
    ];

    public function minuta_empenhos()
    {
        // return $this->hasMany(MinutaEmpenho::class);
    }

    public function modalidade()
    {
        // return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function contrato()
    {
        // return $this->belongsToMany(
        //     'App\Models\Contrato',
        //     'amparo_legal_contrato',
        //     'amparo_legal_id',
        //     'contrato_id'
        // );
    }

    public function contratohistorico()
    {
        // return $this->belongsToMany(
        //     'App\Models\Contratohistorico',
        //     'amparo_legal_contrato',
        //     'contratohistorico_id',
        //     'amparo_legal_id'
        // );
    }


    public function amparo_legal_restricoes()
    {
        // return $this->belongsToMany(
        //     'App\Models\Contratohistorico',
        //     'amparo_legal_contrato',
        //     'contratohistorico_id',
        //     'amparo_legal_id'
        // );
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
    public function getModalidadeIdAttribute($value){
        $retorno = $this->formatarAtributoModalidadeId($value);
        return $retorno;
    }

    // Métodos que auxiliam os mutators
    public function formatarAtributoModalidadeId($id){
        $retorno = Codigoitem::find($id);
        if($retorno){return $retorno->descricao;}
        else {return null;}
    }
}
