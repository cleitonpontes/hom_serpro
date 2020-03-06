<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Saldohistoricoitem extends Model
{
    use CrudTrait;
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logName = 'saldohistoricoitens';
    use SoftDeletes;


    protected $table = 'saldohistoricoitens';


    protected $fillable = [
        'saldoable_type',
        'saldoable_id',
        'contratoitem_id',
        'tiposaldo_id',
        'quantidade',
        'valorunitario',
        'valortotal'
    ];

    public function getContratoItem()
    {
        if ($this->contratoitem_id) {
            $contratoitem = Contratoitem::find($this->contratoitem_id);
            return $contratoitem->item->codigo_siasg . " - " . $contratoitem->item->descricao;
        } else {
            return '';
        }
    }

    public function getContrato()
    {
        if ($this->contratoitem_id) {
            $contratoitem = Contratoitem::find($this->contratoitem_id);
            return $contratoitem->contrato->numero;
        } else {
            return '';
        }
    }

    public function getTipoItem()
    {
        if ($this->contratoitem_id) {
            $contratoitem = Contratoitem::find($this->contratoitem_id);
            return $contratoitem->tipo->descricao;
        } else {
            return '';
        }
    }

    public function getDescricaoComplementar()
    {
        if ($this->contratoitem_id) {
            $contratoitem = Contratoitem::find($this->contratoitem_id);
            return $contratoitem->descricao_complementar;
        } else {
            return '';
        }
    }

    public function getTipoSaldo()
    {
        if ($this->tiposaldo_id) {
            $tiposaldo = Codigoitem::find($this->tiposaldo_id);
            return $tiposaldo->descricao;
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


    public function contratoItem()
    {
        return $this->belongsTo(Contratoitem::class, 'contratoitem_id');
    }


    public function tipoSaldo()
    {
        return $this->belongsTo(Codigoitem::class, 'tiposaldo_id');
    }

    public function saldoable()
    {
        return $this->morphTo();
    }
}
