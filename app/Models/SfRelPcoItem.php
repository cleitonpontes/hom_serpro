<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfRelPcoItem extends Model
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
    protected $table = 'sfrelpcoitem';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfded_id',
        'numseqpai',
        'numseqitem'
    ];

    public function sfdeducao()
    {
        return $this->belongsTo(SfDeducao::class, 'sfded_id');
    }
}
