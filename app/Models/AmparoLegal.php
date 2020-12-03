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



    public function amparo_legal_restricoes()
    {


        // return $this->belongsToMany(AmparoLegal::class,
        // 'amparo_legal_restricoes',
        // 'amparo_legal_id', 'tipo_restricao_id');


        return $this->hasMany(AmparoLegalRestricao::class);
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
    public function getRestricoes(){

        // return

        $arrayDescricoesCoditoitens = AmparoLegalRestricao::where('amparo_legal_id', $this->id)
        ->select('codigoitens.descricao')
        ->join('codigoitens', 'codigoitens.id', '=', 'amparo_legal_restricoes.tipo_restricao_id')
        // ->pluck('codigoitens.descricao', 'codigoitens.id')
        // ->toArray();
        ->get();

        // dd($arrayDescricoesCoditoitens);


        $resultado = '';
        foreach($arrayDescricoesCoditoitens as $descricaoCodigoitem){


            // dd($descricaoCodigoitem['descricao']);

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
}
