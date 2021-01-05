<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Models\CompraItemUnidade;
use App\Models\Contratoitem;
use Spatie\Activitylog\Traits\LogsActivity;

class Comprasitemunidadecontratoitens extends Model
{

    use CrudTrait;
    use LogsActivity;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'compras_item_unidade_contratoitens';
    protected $primaryKey = 'id';
    public $timestamps = false;
    // protected $guarded = ['id'];

    protected $fillable = [
        'compra_item_unidade_id',
        'contratoitem_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function compraitemunidade()
    {
        return $this->belongsTo(CompraItemUnidade::class, 'compra_item_unidade_id', 'id');
    }

    public function contratoitem()
    {
        return $this->belongsTo(Contratoitem::class, 'contratoitem_id', 'id');
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
