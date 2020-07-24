<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfAcrescimo extends Model
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
    protected $table = 'sfacrescimo';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfded_id',
        'sfencargos_id',
        'sfdadospgto_id',
        'tpacrescimo',
        'vlr',
        'numempe',
        'codsubitemempe',
        'codfontrecur',
        'codctgogasto',
        'txtinscra',
        'numclassa',
        'txtinscrb',
        'numclassb',
        'tipo'
    ];

    public function sfdeducao()
    {
        return $this->belongsTo(SfDeducao::class, 'sfded_id');
    }
    public function sfencargos()
    {
        return $this->belongsTo(SfEncargos::class, 'sfencargos_id');
    }

    public function sfdadospgto()
    {
        return $this->belongsTo(SfDadosPgto::class, 'sfdadospgto_id');
    }

}
