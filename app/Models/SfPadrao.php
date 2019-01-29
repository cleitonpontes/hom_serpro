<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SfPadrao extends Model
{

    /**
     * Informa que não utilizará os campos create_at e update_at do Laravel
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'sfpadrao';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'fk',
        'categoriapadrao',
        'decricaopadrao',
        'codugemit',
        'anodh',
        'codtipodh',
        'numdh',
        'dtemis',
        'txtmotivo',
        'msgretorno',
        'tipo',
        'situacao'
    ];
}
