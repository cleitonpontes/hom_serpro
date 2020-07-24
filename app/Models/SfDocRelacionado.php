<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sfDocRelacionado extends Model
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
    protected $table = 'sfdocrelacionado';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfdadosbasicos_id',
        'codugemit',
        'numdocrelacionado'
    ];

    public function dadosBasicos()
    {
        return $this->belongsTo(SfDadosBasicos::class, 'sfdadosbasicos_id');
    }
}
