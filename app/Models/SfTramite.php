<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfTramite extends Model
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
    protected $table = 'sftramite';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfdadosbasicos_id',
        'txtlocal',
        'dtentrada',
        'dtsaida'
    ];

    public function dadosBasicos()
    {
        return $this->belongsTo(SfDadosBasicos::class, 'sfdadosbasicos_id');
    }
}
