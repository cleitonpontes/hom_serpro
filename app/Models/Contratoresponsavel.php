<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoresponsavel extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'responsavel';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoresponsaveis';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'user_id',
        'funcao_id',
        'instalacao_id',
        'portaria',
        'situacao',
        'data_inicio',
        'data_fim',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getContrato()
    {
        if($this->contrato_id){
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        }else{
            return '';
        }
    }
    public function getUser()
    {
        if($this->user_id){
            $user = BackpackUser::find($this->user_id);
            return $user->cpf . ' - ' . $user->name;
        }else{
            return '';
        }
    }
    public function getFuncao()
    {
        if($this->funcao_id){
            $funcao = Codigoitem::find($this->funcao_id);
            return $funcao->descricao;
        }else{
            return '';
        }
    }

    public function getInstalacao()
    {
        if($this->instalacao_id){
            $instalacao = Instalacao::find($this->instalacao_id);
            return $instalacao->nome;
        }else{
            return '';
        }
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

    public function user()
    {
        return $this->belongsTo(BackpackUser::class, 'user_id');
    }

    public function funcao()
    {
        return $this->belongsTo(Codigoitem::class, 'funcao_id');
    }

    public function instalacao()
    {
        return $this->belongsTo(Instalacao::class, 'instalacao_id');
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
