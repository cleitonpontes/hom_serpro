<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoitem extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'contratoitens';
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoitens';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [
        'contrato_id',
        'tipo_id',
        'grupo_id',
        'catmatseritem_id',
        'descricao_complementar',
        'quantidade',
        'valorunitario',
        'valortotal',
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getContrato()
    {
        if($this->contrato_id){
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        }else{
            return '';
        }
    }

    public function getTipo()
    {
        if ($this->tipo_id) {
            $tipo = Codigoitem::find($this->tipo_id);

            return $tipo->descricao;
        } else {
            return '';
        }
    }

    public function getCatmatseritem()
    {
        if ($this->catmatseritem_id) {
            $item = Catmatseritem::find($this->catmatseritem_id);

            return $item->codigo_siasg .' - '. $item->descricao;
        } else {
            return '';
        }
    }

    public function getCatmatsergrupo()
    {
        if ($this->grupo_id) {
            $grupo = Catmatsergrupo::find($this->grupo_id);

            return $grupo->descricao;
        } else {
            return '';
        }
    }

    public function formatValorUnitarioItem()
    {
        return 'R$ ' . number_format($this->valorunitario, 2, ',', '.');
    }

    public function formatValorTotalItem()
    {
        return 'R$ ' . number_format($this->valortotal, 2, ',', '.');
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
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Catmatsergrupo::class, 'grupo_id');
    }

    public function item()
    {
        return $this->belongsTo(Catmatseritem::class, 'catmatseritem_id');
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
