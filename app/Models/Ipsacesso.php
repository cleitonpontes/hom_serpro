<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Ipsacesso extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'ipsacesso';
    protected $primaryKey = 'id';
    public $timestamps = false;
    // protected $guarded = ['id'];

    protected $fillable = [
        'orgao_id',
        'unidade_id',
        'ips'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getOrgao()
    {
        $retorno = '-';

        if ($this->orgao) {
            $orgao = Orgao::find($this->orgao_id);
            $retorno = $orgao->nome;
            
        }

        return $retorno;
    }

    public function getUnidade()
    {
        $retorno = '-';

        if ($this->unidade) {
            $unidade = Unidade::find($this->unidade_id);
            $retorno = $unidade->codigo . ' - ' . $unidade->nomeresumido;
            
        }

        return $retorno;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
    }

    public function orgao()
    {
        return $this->belongsTo(Orgao::class, 'orgao_id', 'id');
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
