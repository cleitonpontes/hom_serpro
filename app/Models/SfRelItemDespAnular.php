<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfRelItemDespAnular extends Model
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
    protected $table = 'sfrelitemdespanular';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfdespanular_id',
        'numseqpai',
        'numseqitem',
        'vlr'
    ];
}
