<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfDeducao extends Model
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
    protected $table = 'sfdeducao';

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
        'dtvenc',
        'dtpgtoreceb',
        'codugpgto',
        'vlr',
        'indrliquidado',
        'txtinscra',
        'numclassa',
        'txtinscrb',
        'numclassb',
        'txtinscrc',
        'numclassc',
        'txtinscrd',
        'numclassd'
    ];


    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }
}
