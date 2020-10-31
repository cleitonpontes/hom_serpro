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

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected static $logFillable = true;
    protected static $logName = 'compra_items';

    protected $table = 'compra_items';

    protected $guarded = [
        'id'
    ];

    protected $fillable = [
        'compra_id',
        'tipo_item_id',
        'catmatseritem_id',
        'fornecedor_id',
        'unidade_autorizada_id',
        'descricaodetalhada',
        'quantidade',
        'valorunitario',
        'valortotal',
        'qtd_restante'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function gravaCompraItem($params){

        $this->compra_id = $params['compra_id'];
        $this->tipo_item_id = $params['tipo_item_id'];
        $this->catmatseritem_id = $params['catmatseritem_id'];
        $this->fornecedor_id = $params['fornecedor_id'];
        $this->unidade_autorizada_id = $params['unidade_autorizada_id'];
        $this->descricaodetalhada = $params['descricaodetalhada'];
        $this->quantidade = $params['quantidade'];
        $this->qtd_total = $params['quantidade'];
        $this->valorunitario = $params['valorunitario'];
        $this->valortotal = $params['valortotal'];

        $this->save($params);
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

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function tipo_item()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_item_id');
    }

    public function unidade_autorizada()
    {
        return $this->belongsTo(Unidade::class, 'unidade_autorizada_id');
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
