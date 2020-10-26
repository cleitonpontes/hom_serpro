<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Lancamento extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'lancamentos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contratoterceirizado_id', 'encargo_id', 'valor', 'movimentacao_id'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    // public function getQuantidadeLancamentosByIdMovimentacao($idMovimentacao){
    //     return $quantidade = Lancamento::where('movimentacao_id', $idMovimentacao)->count();
    // }
    public function getValorTotalLancamentosByIdMovimentacao($idMovimentacao){
        $valorTotal = Lancamento::where('movimentacao_id', '=', $idMovimentacao)->sum('valor');
        // \Log::info('total mov = '.$valorTotal);
        return $valorTotal;
    }
    public function getSalarioContratoTerceirizado(){
        $objContratoTerceirizado = Contratoterceirizado::find($this->contratoterceirizado_id);
        return $objContratoTerceirizado->salario;
    }
    public function getNomePessoaContratoTerceirizado(){
        $objContratoTerceirizado = Contratoterceirizado::find($this->contratoterceirizado_id);
        return $objContratoTerceirizado->nome;
    }
    public function getTipoEncargo(){
        $objEncargo = Encargo::find($this->encargo_id);
        $objCodigoItem = Codigoitem::find($objEncargo->tipo_id);
        return $descricao= $objCodigoItem->descricao;
    }
    public function getPercentualEncargo(){
        $objEncargo = Encargo::find($this->encargo_id);
        return $objEncargo->percentual;
    }
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
