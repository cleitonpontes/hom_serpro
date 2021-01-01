<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CompraItemFornecedor extends Model
{
    use LogsActivity;

    protected $table = 'compra_item_fornecedor';

    public $timestamps = true;

    protected $fillable = [
        'compra_item_id',
        'fornecedor_id',
        'ni_fornecedor',
        'classificacao',
        'situacao_sicaf',
        'quantidade_homologada_vencedor',
        'valor_unitario',
        'valor_negociado',
        'quantidade_empenhada'
    ];



    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function compraItens()
    {
        return $this->belongsTo(CompraItem::class, 'compra_item_id');
    }

}
