<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfPcoItem extends Model
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
    protected $table = 'sfpcoitem';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpco_id',
        'numseqitem',
        'codsubitemempe',
        'indrliquidado',
        'vlr',
        'txtinscra',
        'numclassa',
        'txtinscrb',
        'numclassb',
        'txtinscrc',
        'numclassc'
    ];
}
