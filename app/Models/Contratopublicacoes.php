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

    public function retornaPublicacoesEnviadas(){

        $status_id = Codigoitem::whereHas('codigo', function ($query) {
            $query->where('descricao', '=', 'Situacao Publicacao');
        })
            ->where('descres', '=', '01')
            ->first()->id;

        return $this->whereNotNull('oficio_id')->where('status_publicacao_id',$status_id)->get();
    }


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
