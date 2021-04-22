<?php

namespace App\Models;

use App\Http\Traits\BuscaCodigoItens;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use DB;

class Contratounidadedescentralizada extends Model
{
    use CrudTrait;
    use BuscaCodigoItens;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratounidadesdescentralizadas';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['contrato_id', 'unidade_id'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getContrato(){
        if (!$this->contrato_id)
           return '';
        return $this->contrato()->first()->numero;
    }
    public function getUnidade(){
        if (!$this->contrato_id)
           return '';
        return $this->unidade()->first()->codigo.' - '.$this->unidade()->first()->nome;
    }

    public function getValorEmpenhado(){
        $situacao_empenho_emitido_id = $this->retornaIdCodigoItem('Situações Minuta Empenho', 'EMPENHO EMITIDO');

        return ContratoItemMinutaEmpenho::distinct()
                                        ->select(DB::raw("CONCAT('R$ ' , coalesce(sum(contrato_item_minuta_empenho.valor ),'0.00')) AS valor"))
                                        ->join('contratoitens AS ci','ci.id', '=', 'contrato_item_minuta_empenho.contrato_item_id')
                                        ->join('minutaempenhos AS me','me.id', '=', 'contrato_item_minuta_empenho.minutaempenho_id')
                                        ->where('me.situacao_id', $situacao_empenho_emitido_id)
                                        ->where('ci.contrato_id', $this->contrato_id)
                                        ->where('me.unidade_id', $this->unidade()->first()->id)->get()->first()->valor;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
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
