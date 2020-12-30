<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfPsoItem extends Model
{
    use LogsActivity;

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpso_id',
        'numseqitem',
        'indrliquidado',
        'vlr',
        'codfontrecur',
        'codctgogasto',
        'txtinscra',
        'numclassa',
        'txtinscrb',
        'numclassb',
        'txtinscrc',
        'numclassc',
        'txtinscrd',
        'numclassd'
    ];

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
    protected $table = 'sfpsoitem';

    public function pso()
    {
        return $this->belongsTo(SfPso::class, 'sfpso_id');
    }
}
