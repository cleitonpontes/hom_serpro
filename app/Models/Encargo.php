<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Encargo extends Model
{

    protected $primaryKey = 'id';

    use CrudTrait;
    use LogsActivity;

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
    public static function getIdCodigoItemByDescricao($descricao){
        $objCodigoItem = Codigoitem::where('descricao', '=', $descricao)->first();
        return $id = $objCodigoItem->id;
    }
    public static function getIdEncargoByNomeEncargo($nomeEncargo){
        // bucar em codigoitens, pela descrição, pegar o id e buscar o tipo id em encargos pelo id
        $obj = \DB::table('codigoitens')
        ->select('encargos.id')
        ->where('codigoitens.descricao','=',$nomeEncargo)
        ->join('encargos', 'encargos.tipo_id', '=', 'codigoitens.id')
        ->first();
        if( !is_object($obj) ){
            echo $nomeEncargo.' -> Encargo não localizado.';
            exit;
        }
        return $obj->id;
        // $objCodigoItem = Codigoitem::where('descricao', '=', $descricao)->first();
        // return $id = $objCodigoItem->id;
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

}
