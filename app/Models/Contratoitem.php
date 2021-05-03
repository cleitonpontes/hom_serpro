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
    use SoftDeletes;

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
        'periodicidade',
        'data_inicio',
        'valorunitario',
        'valortotal',
        'numero_item_compra',
        'contratohistorico_id'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function atualizaSaldoContratoItem(Saldohistoricoitem $saldohistoricoitem)
    {
        $saldoitens = Saldohistoricoitem::where('contratoitem_id', $saldohistoricoitem->contratoitem_id)
            ->orderBy('contratohistorico.data_assinatura', 'ASC')
            ->join('contratohistorico', 'contratohistorico.id', '=', 'saldohistoricoitens.saldoable_id')
            ->get();

        foreach ($saldoitens as $saldoitem) {
            $contratoitem = Contratoitem::find($saldoitem->contratoitem_id);
            $contratoitem->quantidade = $saldoitem->quantidade;
            $contratoitem->valorunitario = $saldoitem->valorunitario;
            $contratoitem->valortotal = $saldoitem->valortotal;
            $contratoitem->periodicidade = $saldoitem->periodicidade;
            $contratoitem->data_inicio = $saldoitem->data_inicio;
            $contratoitem->numero_item_compra = $saldoitem->numero_item_compra;
            $contratoitem->save();
        }
    }

    public function deletaContratoItem(Saldohistoricoitem $saldohistoricoitem)
    {
        $contratoitem = Contratoitem::find($saldohistoricoitem->contratoitem_id);
        $contratoitem->delete();
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

    public function itemAPI()
    {
        return [
                'id' => $this->id,
                'contrato_id' => $this->contrato_id,
                'tipo_id' => $this->getTipo(),
                'grupo_id' => $this->getCatmatsergrupo(),
                'catmatseritem_id' => $this->getCatmatseritem(),
                'descricao_complementar' => $this->descricao_complementar,
                'quantidade' => $this->quantidade,
                'valorunitario' => number_format($this->valorunitario, 2, ',', '.'),
                'valortotal' => number_format($this->valortotal, 2, ',', '.'),
        ];
    }

    public function buscaItensPorContratoId(int $contrato_id, $range)
    {
        $itens = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoitens.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $itens;
    }

    public function buscaItens($range)
    {
        $itens = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratoitens.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $itens;
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
        return $this->belongsToMany(Servico::class, 'contratoitem_servico', 'contratoitem_id', 'servico_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

    public function saldoHistoricoItens()
    {
        return $this->morphToMany(Saldohistoricoitem::class, 'saldoable');
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
