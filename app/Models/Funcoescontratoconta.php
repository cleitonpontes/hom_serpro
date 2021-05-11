<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Funcoescontratoconta extends Model
{
    use CrudTrait;
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoterceirizados';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    // retornar um texto com os salários dos terceirizados desta função e deste contrato
    public function getSalariosDaFuncaoContrato(){
        $contrato_id = \Route::current()->parameter('contrato_id');
        $funcao_id = $this->id;
        $salarios = null;
        $arraySalarios = array();
        // buscar todos os salários de todos os terceirizados com esta funcao e este contrato_id
        $arrayContratosTerceirizados = Contratoterceirizado::where('funcao_id', $funcao_id)
        ->where('contrato_id', $contrato_id)
        ->get();
        foreach($arrayContratosTerceirizados as $objContratoTerceirizado){
            $salario = $objContratoTerceirizado->salario;
            if( $salarios == null ){
                $salarios  = $salario;
                if( !in_array($salario, $arraySalarios) ){
                    array_push($arraySalarios, $salario);
                }
            } else {
                if( !in_array($salario, $arraySalarios) ){
                    $salarios .= ' / '.$salario;
                    array_push($arraySalarios, $salario);
                }
            }
        }
        return $salarios;
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
