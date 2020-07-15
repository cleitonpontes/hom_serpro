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

    public function sfItemRecolhimento()
    {
        return $this->hasMany(SfItemRecolhimento::class, 'sfded_id');
    }

    public function sfPreDoc()
    {
        return $this->hasOne(SfPredoc::class, 'sfded_id');
    }

    public function sfAcrescimo()
    {
        return $this->hasMany(SfAcrescimo::class, 'sfded_id');
    }

    public function sfRelPcoItem()
    {
        return $this->hasMany(SfPcoItem::class, 'sfded_id');
    }

    public function sfRelPsoItem()
    {
        return $this->hasMany(SfPsoItem::class, 'sfded_id');
    }

    public function sfRelCredito()
    {
        return $this->hasMany(SfCredito::class, 'sfded_id');
    }
}
