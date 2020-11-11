<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompraItemUnidade extends Model
{
    protected $table = 'compra_item_unidade';
    protected $primaryKey = ['compra_item_id', 'unidade_id'];

    protected $fillable = [
        'compra_item_id',
        'unidade_id',
        'fornecedor_id',
        'quantidade_autorizada',
        'quantidade_saldo',
        'valor_item',
        'valor_total',
        'tipo_uasg'
    ];



    public function unidades()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id');
    }

    public function compraItens()
    {
        return $this->belongsTo(CompraItem::class, 'compra_item_id');
    }

}
