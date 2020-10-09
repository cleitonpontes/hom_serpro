<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

use App\Models\Lancamento;


class Movimentacaocontratoconta extends Model
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
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTotalMovimentacao(){
        $objLancamento = new Lancamento();

        $idMovimentacao= $this->id;


        // vamos pegar todosos lançamentos da movimentacao e somá-los
        return $valorTotal = $objLancamento->getValorTotalLancamentosByIdMovimentacao($idMovimentacao);


    }
    public function getTipoMovimentacao(){
        $objCodigoItem = Codigoitem::find($this->tipo_id);
        return $descricao= $objCodigoItem->descricao;
    }
    public function getIdEncargoByIdCodigoitens($idCodigoitens){
        $obj = \DB::table('encargos')
            ->select('encargos.id')
            ->join('contratoitens', 'contratoitens.id', '=', 'encargos.tipo_id')
            ->where('contratoitens.id', '=', $idCodigoitens)
            ->first();
        return $idEncargo = $obj->id;
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
