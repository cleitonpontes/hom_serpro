<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Unidadeconfiguracao extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'unidadeconfiguracao';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'unidadeconfiguracao';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'unidade_id',
        'user1_id',
        'user2_id',
        'user3_id',
        'user4_id',
        'telefone1',
        'telefone2',
        'email_diario',
        'email_diario_periodicidade',
        'email_diario_texto',
        'email_mensal',
        'email_mensal_dia',
        'email_mensal_texto',
        'padrao_processo_mascara'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getUnidade()
    {
        $unidade = Unidade::find($this->unidade_id);

        return $unidade->codigo . ' - ' . $unidade->nomeresumido;
    }

    public function getUser1()
    {
        $user = BackpackUser::find($this->user1_id);

        return $user->cpf . ' - ' . $user->name;
    }

    public function getUser2()
    {
        if($this->user2_id){
            $user = BackpackUser::find($this->user2_id);
            return $user->cpf . ' - ' . $user->name;
        }else{
            return '';
        }


    }

    public function getUser3()
    {
        if($this->user3_id){
            $user = BackpackUser::find($this->user3_id);
            return $user->cpf . ' - ' . $user->name;
        }else{
            return '';
        }
    }

    public function getUser4()
    {
        if($this->user4_id){
            $user = BackpackUser::find($this->user4_id);
            return $user->cpf . ' - ' . $user->name;
        }else{
            return '';
        }
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function user1()
    {
        return $this->belongsTo(BackpackUser::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(BackpackUser::class, 'user2_id');
    }

    public function user3()
    {
        return $this->belongsTo(BackpackUser::class, 'user3_id');
    }

    public function user4()
    {
        return $this->belongsTo(BackpackUser::class, 'user4_id');
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
