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
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'garantia';

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contratogarantias';
    protected $fillable = [
        'contrato_id',
        'tipo',
        'valor',
        'vencimento'
    ];

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

    public function garantiaAPI()
    {
        return [
            'id' => $this->id, 
            'contrato_id' => $this->contrato_id,
            'tipo' => $this->getTipo(),
            'valor' => number_format($this->valor, 2, ',', '.'),
            'vencimento' => $this->vencimento,
        ];
    }

    public function buscaGarantiasPorContratoId(int $contrato_id, $range)
    {
        $garantias = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->where('contrato_id', $contrato_id)
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratogarantias.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $garantias;
    }

    public function buscaGarantias($range)
    {
        $garantias = $this::whereHas('contrato', function ($c){
            $c->whereHas('unidade', function ($u){
                $u->where('sigilo', "=", false);
            });
        })
            ->when($range != null, function ($d) use ($range) {
                $d->whereBetween('contratogarantias.updated_at', [$range[0], $range[1]]);
            })
            ->get();

        return $garantias;
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
