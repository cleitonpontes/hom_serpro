<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfDespesaAnularItem extends Model
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
    protected $table = 'sfdespesaanularitem';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfdespesaanular_id',
        'numseqitem',
        'numempe',
        'codsubitemempe',
        'vlr',
        'txtinscra',
        'numclassa',
        'txtinscrb',
        'numclassb',
        'txtinscrc',
        'numclassc'
    ];
}
