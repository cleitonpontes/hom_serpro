<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Siasgcontrato extends Model
{
    use CrudTrait;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected static $logFillable = true;
    protected static $logName = 'siasgcontratos';

    protected $table = 'siasgcontratos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'compra_id',
        'unidade',
        'tipo_id',
        'numero',
        'ano',
        'codigo_interno',
        'unidadesubrrogacao',
        'mensagem',
        'situacao',
        'json',
        'sisg',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function buscaIdUnidade(string $codigo)
    {
        $unidade = Unidade::where('codigosiasg',$codigo)
            ->first();

        if(!isset($unidade->id)){
            return null;
        }

        return $unidade->id;
    }

    public function buscaIdTipo(string $tipo)
    {
        $codigoitem = Codigoitem::whereHas('codigo', function ($c){
                $c->where('descricao','Tipo de Contrato');
            })
            ->where('descres',$tipo)
            ->first();

        return $codigoitem->id;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function unidadesubrrogacao()
    {
        return $this->belongsTo(Unidade::class, 'unidadesubrrogacao_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
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
