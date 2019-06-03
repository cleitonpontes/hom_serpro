<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
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
        return $naturezadespesa->naturezadespesa->codigo . ' - ' . $naturezadespesa->naturezadespesa->descricao;

    }

    public function formatVlrEmpaliquidar()
    {
        return 'R$ ' . number_format($this->empaliquidar, 2, ',', '.');
    }

    public function formatVlrEmpemliquidacao()
    {
        return 'R$ ' . number_format($this->empemliquidacao, 2, ',', '.');
    }

    public function formatVlrEmpliquidado()
    {
        return 'R$ ' . number_format($this->empliquidado, 2, ',', '.');
    }

    public function formatVlrEmppago()
    {
        return 'R$ ' . number_format($this->emppago, 2, ',', '.');
    }

    public function formatVlrEmpaliqrpnp()
    {
        return 'R$ ' . number_format($this->empaliqrpnp, 2, ',', '.');
    }

    public function formatVlrEmpemliqrpnp()
    {
        return 'R$ ' . number_format($this->empemliqrpnp, 2, ',', '.');
    }

    public function formatVlrEmprpp()
    {
        return 'R$ ' . number_format($this->emprpp, 2, ',', '.');
    }

    public function formatVlrRpnpaliquidar()
    {
        return 'R$ ' . number_format($this->rpnpaliquidar, 2, ',', '.');
    }

    public function formatVlrRpnpaliquidaremliquidacao()
    {
        return 'R$ ' . number_format($this->rpnpaliquidaremliquidacao, 2, ',', '.');
    }

    public function formatVlrRpnpliquidado()
    {
        return 'R$ ' . number_format($this->rpnpliquidado, 2, ',', '.');
    }

    public function formatVlrRpnppago()
    {
        return 'R$ ' . number_format($this->rpnppago, 2, ',', '.');
    }

    public function formatVlrRpnpaliquidarbloq()
    {
        return 'R$ ' . number_format($this->rpnpaliquidarbloq, 2, ',', '.');
    }

    public function formatVlrRpnpaliquidaremliquidbloq()
    {
        return 'R$ ' . number_format($this->rpnpaliquidaremliquidbloq, 2, ',', '.');
    }

    public function formatVlrRpnpcancelado()
    {
        return 'R$ ' . number_format($this->rpnpcancelado, 2, ',', '.');
    }

    public function formatVlrRpnpoutrocancelamento()
    {
        return 'R$ ' . number_format($this->rpnpoutrocancelamento, 2, ',', '.');
    }

    public function formatVlrRpnpemliqoutrocancelamento()
    {
        return 'R$ ' . number_format($this->rpnpemliqoutrocancelamento, 2, ',', '.');
    }

    public function formatVlrRppliquidado()
    {
        return 'R$ ' . number_format($this->rppliquidado, 2, ',', '.');
    }

    public function formatVlrRpppago()
    {
        return 'R$ ' . number_format($this->rpppago, 2, ',', '.');
    }

    public function formatVlrRppcancelado()
    {
        return 'R$ ' . number_format($this->rppcancelado, 2, ',', '.');
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
