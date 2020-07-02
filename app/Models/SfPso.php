<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfPso extends Model
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
    protected $table = 'sfpco';

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
        'txtinscre',
        'numclasse',
        'txtinscrf',
        'numclassf'
    ];



    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }
}
