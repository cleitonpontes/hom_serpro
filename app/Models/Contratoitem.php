<?php

namespace App\Models;

use App\Models\ContratoBase as Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Contratoitem extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'contratoitens';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratoitens';
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

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function atualizaSaldoContratoItem(Saldohistoricoitem $saldohistoricoitem)
    {
        $saldoitens = Saldohistoricoitem::where('contratoitem_id', $saldohistoricoitem->contratoitem_id)
            ->orderBy('created_at','ASC')
            ->get();

        foreach ($saldoitens as $saldoitem){
            $contratoitem = Contratoitem::find($saldoitem->contratoitem_id);
            $contratoitem->quantidade = $saldoitem->quantidade;
            $contratoitem->valorunitario = $saldoitem->valorunitario;
            $contratoitem->valortotal = $saldoitem->valortotal;
            $contratoitem->save();
        }
    }

    public function getContrato()
    {
        if ($this->contrato_id) {
            $contrato = Contrato::find($this->contrato_id);
            return $contrato->numero;
        } else {
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

            return $item->codigo_siasg . ' - ' . $item->descricao;
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

    public function grupo()
    {
        return $this->belongsTo(Catmatsergrupo::class, 'grupo_id');
    }

    public function item()
    {
        return $this->belongsTo(Catmatseritem::class, 'catmatseritem_id');
    }

    public function servicos()
    {
        return $this->belongsToMany( Servico::class, 'contratoitem_servico', 'contratoitem_id', 'servico_id' );
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
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

    public function getDescricaoGrupoAttribute($value)
    {
        return $this->grupo()->first()->descricao;
    }

    public function getDescricaoItemAttribute($value)
    {
        return $this->item()->first()->descricao;
    }

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
