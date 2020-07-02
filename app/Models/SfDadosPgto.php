<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfDadosPgto extends Model
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
    protected $table = 'sfdadospgto';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpadrao_id',
        'codcredordevedor',
        'vlr'
    ];


    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }
}
