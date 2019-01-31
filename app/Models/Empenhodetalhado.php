<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Empenhodetalhado extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'empenhodetalhado';
    use SoftDeletes;

    protected $table = 'empenhodetalhado';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'empenho_id',
        'naturezadespesa_id',
        'naturezasubitem_id',
        'empaliquidar',
        'empemliquidacao',
        'empliquidado',
        'emppago',
        'empaliqrpnp',
        'empemliqrpnp',
        'emprpp',
        'rpnpaliquidar',
        'rpnpaliquidaremliquidacao',
        'rpnpliquidado',
        'rpnppago',
        'rpnpaliquidarbloq',
        'rpnpaliquidaremliquidbloq',
        'rpnpcancelado',
        'rpnpoutrocancelamento',
        'rpnpemliqoutrocancelamento',
        'rppliquidado',
        'rpppago',
        'rppcancelado',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getEmpenho()
    {
        $empenho = Empenho::find($this->empenho_id);
        return $empenho->numero;

    }

    public function getSubitem()
    {
        $subitem = Naturezasubitem::find($this->naturezasubitem_id);
        return $subitem->codigo . " - " . $subitem->descricao;

    }

    public function getNaturezadespesa()
    {
        $naturezadespesa = Naturezasubitem::find($this->naturezasubitem_id);
        return $naturezadespesa->naturezadespesa->codigo;

    }

    public function formatVlrEmpaliquidar()
    {
        return 'R$ ' . number_format($this->empaliquidar, 2, ',', '.');
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
