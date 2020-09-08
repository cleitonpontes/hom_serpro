<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;


class Encargo extends Model
{

    protected $primaryKey = 'id';



    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'encargos';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'tipo_id',
        'percentual',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getDescricaoCodigoItem(){
        $objCodigoItem = Codigoitem::find($this->tipo_id);
        return $descricao= $objCodigoItem->descricao;
    }
    public function formatPercentual()
    {
        return number_format($this->percentual, 2, ',', '.');
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
    // public function getTipoIdAttribute($value){
    //     $retorno = $this->formatarAtributoTipoId($value);
    //     return $retorno;
    // }

    // // Métodos que auxiliam os mutators
    // public function formatarAtributoTipoId($id){
    //     $retorno = Codigoitem::find($id);
    //     if($retorno){return $retorno->descricao;}
    //     else {return null;}
    // }


}
