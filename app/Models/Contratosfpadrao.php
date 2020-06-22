<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratosfpadrao extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sfpadrao';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'id',
        'fk',
        'categoriapadrao',
        'decricaopadrao',
        'codugemit',
        'anodh',
        'codtipodh',
        'numdh',
        'dtemis',
        'txtmotivo',
        'msgretorno',
        'tipo',
        'situacao'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getNumeroContrato()
    {
        $contrato = Contrato::find($this->fk);
        return $contrato->numero;

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function dadosBasicos()
    {
        return $this->hasOne(SfDadosBasicos::class, 'sfpadrao_id');
    }

    public function pco()
    {
        return $this->hasMany(SfPco::class, 'sfpadrao_id');
    }

    public function centroCusto()
    {
        return $this->hasMany(Sfcentrocusto::class, 'sfpadrao_id');
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
