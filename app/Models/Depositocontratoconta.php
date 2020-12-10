<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Depositocontratoconta extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'movimentacaocontratocontas';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contratoconta_id',
        'tipo_id',
        'mes_competencia',
        'ano_competencia',
        'valor_total_mes_ano',
        'situacao_movimentacao',
        'user_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTipoMovimentacao(){
        $objCodigoItem = Codigoitem::find($this->tipo_id);
        return $descricao= $objCodigoItem->descricao;
    }
    // public function getTipoEncargo(){
    //     $objEncargo = Encargo::find($this->encargo_id);
    //     $objCodigoItem = Codigoitem::find($objEncargo->tipo_id);
    //     return $descricao= $objCodigoItem->descricao;
    // }
    public function formatValor(){
        return number_format($this->valor, 2, ',', '.');
    }


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
