<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfRelEncargoItem extends Model
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
    protected $table = 'sfrelencargoitem';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfcompensacao_id',
        'numseqitem'
    ];

    public function sfDespesaAnularItem()
    {
        return $this->belongsTo(SfCompensacao::class, 'sfcompensacao_id');
    }
}
