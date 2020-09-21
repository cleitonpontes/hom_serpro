<?php

namespace App\Models;

use App\Models\ContratoBase as Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratogarantia extends Model
{
    use CrudTrait;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'garantia';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratogarantias';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'tipo',
        'valor',
        'vencimento'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getTipo()
    {
        return $this->tipo()->first()->descricao;
    }

    public function formatVlr()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
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

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo');
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

    public function getDescricaoTipoAttribute($value)
    {
        return $this->tipo()->first()->descricao;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
