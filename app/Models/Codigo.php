<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Codigo extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'codigo';
    use SoftDeletes;
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

        $button = '<div class="btn-group">
                        <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
                            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears"></i> 
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                            <ul class="dropdown-menu" >
                                <li><a href="/admin/codigo/'.$this->id.'/codigoitem">Itens</a></li>
                            </ul>
                    </div>';

//        return '<a class="btn btn-xs btn-default" href="/admin/codigo/'.$this->id.'/codigoitem"
//        data-toggle="tooltip" title="Código Itens"><i class="fa fa-navicon"></i> Itens</a>';

        return $button;

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
