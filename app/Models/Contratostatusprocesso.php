<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\Codigoitem;

class Contratostatusprocesso extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contratostatusprocesso';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratostatusprocessos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'processo',
        'data_inicio',
        'data_fim',
        'status',
        'unidade',
        'situacao'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getQuantidadeDias(){
        $nomeSituacao = $this->getNomeSituacao();
        if($nomeSituacao=='Finalizado'){
            // \Log::info($nomeSituacao);
            $dataInicio = $this->data_inicio;
            $dataFim = $this->data_fim;
            $diferenca = strtotime($dataFim) - strtotime($dataInicio);
            $dias = floor($diferenca / (60 * 60 * 24) );
        } else {
            $dias = null;
        }
        return $dias;
    }
    public function getContrato()
    {
        if($this->contrato_id){
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        }else{
            return '';
        }
    }
    public function getNomeSituacao()
    {
        if($this->id){
            $array = Contratostatusprocesso::where('contratostatusprocessos.id', '=', $this->id)
            ->join('codigoitens', 'codigoitens.id', '=', 'contratostatusprocessos.situacao')
            ->select('codigoitens.descres')
            ->get();
            $nomeSituacao = $array[0]->descres;
            return $nomeSituacao;
        }else{
            return '';
        }
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
