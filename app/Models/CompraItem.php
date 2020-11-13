<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CompraItem extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    public const MATERIAL = [149, 194];
    public const SERVICO = [150, 195];

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    protected static $logName = 'compra_items';

    protected $table = 'compra_items';

    protected $fillable = [
        'compra_id',
        'tipo_item_id',
        'catmatseritem_id',
        'descricaodetalhada',
        'valorunitario',
        'qtd_total',
        'valortotal',
        'qtd_restante',
        'numero'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */


    public function gravaCompraItemSisrp($params,$catmatseritem,$dadosata)
    {
        $tipo = ['S' => $this::SERVICO[0], 'M' => $this::MATERIAL[0]];

        $this->compra_id = (int)$params['compra_id'];
        $this->tipo_item_id = $tipo[$dadosata->tipo];
        $this->catmatseritem_id = $catmatseritem->id;
        $this->descricaodetalhada = $dadosata->descricaoDetalhada;
        $this->numero = $dadosata->numeroItem;

        $this->save();
        return $this->id;
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function catmatseritem()
    {
        return $this->belongsTo(Catmatseritem::class, 'catmatseritem_id');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

//    public function fornecedor()
//    {
//        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
//    }

    public function tipo_item()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_item_id');
    }

    public function unidade_autorizada()
    {
        return $this->belongsTo(Unidade::class, 'unidade_autorizada_id');
    }

    public function unidade()
    {
        return $this->belongsToMany(
            'App\Models\CompraItem',
            'compra_item_unidade',
            'unidade_id',
            'compra_item_id'
        );
    }

    public function fornecedor()
    {
        return $this->belongsToMany(
            'App\Models\CompraItem',
            'compra_item_unidade',
            'fornecedor_id',
            'compra_item_id'
        );
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
