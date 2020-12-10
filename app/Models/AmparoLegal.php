<?php

namespace App\Models;

// use App\Models\AmparoLegalRestricao;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AmparoLegal extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    protected static $logName = 'amparo_legal';

    protected $table = 'amparo_legal';
    protected $guarded = [
        'id'
    ];

    // protected $colunarestricoes = [];

    protected $fillable = [
        'codigo',
        'modalidade_id',
        'ato_normativo',
        'artigo',
        'paragrafo',
        'inciso',
        'alinea',
        // 'codigo_restricao'
    ];

    public function minuta_empenhos()
    {
        // return $this->hasMany(MinutaEmpenho::class);
    }

    public function modalidade()
    {
        // return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function contrato()
    {
        // return $this->belongsToMany(
        //     'App\Models\Contrato',
        //     'amparo_legal_contrato',
        //     'amparo_legal_id',
        //     'contrato_id'
        // );
    }

    public function contratohistorico()
    {
        // return $this->belongsToMany(
        //     'App\Models\Contratohistorico',
        //     'amparo_legal_contrato',
        //     'contratohistorico_id',
        //     'amparo_legal_id'
        // );
    }


    public function codigoitens(){
        return $this->belongsToMany(Codigoitem::class,
        'amparo_legal_restricoes',
        'amparo_legal_id',
        'tipo_restricao_id',
    );
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getModalidade(){
        $modalidade_id = $this->modalidade_id;
        $obj = Codigoitem::find($modalidade_id);
        if($obj){return $obj->descricao;}
        else {return null;}
    }
    public function getCodigoRestricao(){
        $id = $this->id;
        $array = AmparoLegalRestricao::where('amparo_legal_id', $id)->get()->first();
        return $codigoRestricao = $array['codigo_restricao'];
    }
    public function getRestricoes(){


        $arrayDescricoesCoditoitens = AmparoLegalRestricao::where('amparo_legal_id', $this->id)
        ->select('codigoitens.descricao')
        ->join('codigoitens', 'codigoitens.id', '=', 'amparo_legal_restricoes.tipo_restricao_id')
        ->get();

        $resultado = '';
        foreach($arrayDescricoesCoditoitens as $descricaoCodigoitem){
            $resultado .= $descricaoCodigoitem['descricao'].', ';
        }
        return $resultado;
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
    // public function getModalidadeIdAttribute($value)
    // {
    //     return $descricao = Codigoitem::where('id', $value)->first()->descricao;
    // }

}
