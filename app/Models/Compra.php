<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Compra extends Model
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
    protected static $logName = 'compras';

    protected $table = 'compras';
    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'unidade_origem_id',
        'unidade_subrrogada_id',
        'modalidade_id',
        'tipo_compra_id',
        'numero_ano',
        'inciso',
        'lei'
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

    public function compra_item()
    {
        return $this->hasMany(CompraItem::class);
    }

    public function minuta_empenhos()
    {
        return $this->hasMany(MinutaEmpenho::class);
    }

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'modalidade_id');
    }

    public function tipo_compra()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_compra_id');
    }

    public function unidade_origem()
    {
        return $this->belongsTo(Unidade::class, 'unidade_origem_id');
    }

    public function unidade_subrrogada()
    {
        return $this->belongsTo(Unidade::class, 'unidade_subrrogada_id');
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
