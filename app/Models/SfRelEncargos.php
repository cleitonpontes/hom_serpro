<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfRelEncargos extends Model
{
    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'sfrelencargos';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfdespesaanularitem_id',
        'numseqitem'
    ];

    public function sfDespesaAnularItem()
    {
        return $this->belongsTo(SfDespesaAnularItem::class, 'sfdespesaanularitem_id');
    }
}
