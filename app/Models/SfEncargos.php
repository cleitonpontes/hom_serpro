<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfEncargos extends Model
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
    protected $table = 'sfencargo';

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
        'dtvenc',
        'dtpgtoreceb',
        'codugpgto',
        'vlr',
        'codugempe',
        'numempe',
        'codsubitemempe',
        'txtinscra',
        'numclassa',
        'txtinscrb',
        'numclassb',
        'txtinscrc',
        'numclassc'
    ];


    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }

    public function sfItemRecolhimento()
    {
        return $this->hasMany(SfItemRecolhimento::class, 'sfencargos_id');
    }

    public function sfPreDoc()
    {
        return $this->hasOne(SfPredoc::class, 'sfencargos_id');
    }

    public function sfAcrescimo()
    {
        return $this->hasMany(SfAcrescimo::class, 'sfencargos_id');
    }
}
