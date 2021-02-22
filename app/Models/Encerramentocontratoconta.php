<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Encerramentocontratoconta extends Model
{
    use CrudTrait;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratocontas';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'data_encerramento',
        'user_id_encerramento',
        'obs_encerramento',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    // public function getTipoMovimentacao(){
    //     $objCodigoItem = Codigoitem::find($this->tipo_id);
    //     return $descricao= $objCodigoItem->descricao;
    // }
    // public function getTipoEncargo(){
    //     $objEncargo = Encargo::find($this->encargo_id);
    //     $objCodigoItem = Codigoitem::find($objEncargo->tipo_id);
    //     return $descricao= $objCodigoItem->descricao;
    // }
    // public function formatValor(){
    //     return number_format($this->valor, 2, ',', '.');
    // }


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
