<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfItemRecolhimento extends Model
{
    use LogsActivity;
    
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
    protected $table = 'sfitemrecolhimento';

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
        'numseqitem',
        'codrecolhedor',
        'vlr',
        'vlrbasecalculo',
        'vlrmulta',
        'vlrjuros',
        'vlroutrasent',
        'vlratmmultajuros'
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
