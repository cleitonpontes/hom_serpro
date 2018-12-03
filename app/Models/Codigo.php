<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Codigo extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'codigo';
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'codigos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['descricao','visivel'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getVisivel()
    {
        if($this->visivel == true){
            $visivel = 'Sim';
        }else{
            $visivel = 'Não';
        }

        return $visivel;
    }

    public function codigoItens($crud = false)
    {
        return '<a class="btn btn-xs btn-default" href="/admin/codigo/'.$this->id.'/codigoitem" 
        data-toggle="tooltip" title="Código Itens"><i class="fa fa-navicon"></i> Itens</a>';

    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function codigoitem(){

        return $this->hasMany(Codigoitem::class, 'codigo_id');

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
