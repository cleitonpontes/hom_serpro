<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfParcela extends Model
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
    protected $table = 'sfparcela';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfcronbaixapatrimonial_id',
        'numparcela',
        'dtprevista',
        'vlr'
    ];

    public function pco()
    {
        return $this->belongsTo(SfCronBaixaPatrimonial::class, 'sfcronbaixapatrimonial_id');
    }

}
