<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CompraItemUnidadeContratoItem extends Model
{
    use LogsActivity;

    protected $table = 'compras_item_unidade_contratoitens';

    protected $fillable = [
        'compra_item_unidade_id',
        'contratoitem_id'
    ];

    public function compra_item_unidade()
    {
        return $this->belongsTo(CompraItemUnidade::class, 'compra_item_unidade_id');
    }

    public function contratoItem()
    {
        return $this->belongsTo(Contratoitem::class, 'contratoitem_id');
    }

}
