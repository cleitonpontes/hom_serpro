<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class SfCompensacao extends Model
{
    use LogsActivity;

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
    protected $table = 'sfcompensacao';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpadrao_id',
        'numseqitem',
        'codsit',
        'vlr',
        'txtinscra',
        'numclassa'
    ];


    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }

    public function sfRelDeducaoItem()
    {
        return $this->hasOne(SfRelDeducaoItem::class, 'sfcompensacao_id');
    }

    public function sfRelEncargoItem()
    {
        return $this->hasOne(SfRelEncargoItem::class, 'sfcompensacao_id');
    }
}
