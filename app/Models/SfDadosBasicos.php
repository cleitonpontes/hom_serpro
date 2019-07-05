<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfDadosBasicos extends Model
{

    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Identifica o campo chave primária da tabela
     *
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'sfdadosbasicos';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpadrao_id',
        'dtemis',
        'dtvenc',
        'codugpgto',
        'vlr',
        'txtobser',
        'txtinfoadic',
        'vlrtaxacambio',
        'txtprocesso',
        'dtateste',
        'codcredordevedor',
        'dtpgtoreceb'
    ];

    public function docOrigem()
    {
        return $this->hasMany(SfDocOrigem::class, 'sfdadosbasicos_id');
    }

    public function sfpadrao()
    {
        return $this->belongsTo(SfPadrao::class, 'sfpadrao_id');
    }
}
