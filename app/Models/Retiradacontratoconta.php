<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Retiradacontratoconta extends Model
{
    use CrudTrait;
    use LogsActivity;

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
    public function formatValor(){
        return number_format($this->valor, 2, ',', '.');
    }
    public function getContratosTerceirizadosParaCombo($contrato_id){
        return $arrayContratosTerceirizados = Contratoterceirizado::where('contrato_id','=',$contrato_id)->pluck('nome', 'id')->toArray();
    }
    public function getEncargosParaCombo(){
        // Os dados da combo serão fixos
        return $arrayObjetosEncargoParaCombo = array(
            'Décimo Terceiro' => 'Décimo Terceiro',
            'Demissão' => 'Demissão',
            'Férias' => 'Férias',
        );
        // // buscar os encargos para calcularmos e exibirmos no formulário
        // return $arrayObjetosEncargos = Codigoitem::whereHas('codigo', function ($query) {
        //     $query->where('descricao', '=', 'Tipo Encargos');
        // })
        // ->join('encargos', 'encargos.tipo_id', '=', 'codigoitens.id')
        // ->orderBy('descricao')
        // ->pluck('codigoitens.descricao', 'codigoitens.id')
        // // ->get()
        // ->toArray();
        // dd($arrayObjetosEncargos);
    }


    // // teste botao
    // public function openGoogle($crud = false)
    // {
    //     return '<a class="btn btn-sm btn-link" target="_blank" href="http://google.com?q='.urlencode($this->text).'" data-toggle="tooltip" title="Just a demo custom button."><i class="fa fa-search"></i> Google it</a>';
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
