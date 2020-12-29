<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfOutrosLanc extends Model
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
    protected $table = 'sfoutroslanc';

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
        'indrliquidado',
        'vlr',
        'indrtemcontrato',
        'txtinscra',
        'numclassa',
        'txtinscrb',
        'numclassb',
        'txtinscrc',
        'numclassc',
        'txtinscrd',
        'numclassd',
        'tpnormalestorno'
    ];



    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }

    public function cronBaixaPatrimonial()
    {
        return $this->hasMany(SfCronBaixaPatrimonial::class, 'outroslanc_id');
    }
}
