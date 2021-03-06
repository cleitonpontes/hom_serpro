<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfPcoItem extends Model
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
        'numempe',
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

    public function pco()
    {
        return $this->belongsTo(SfPco::class, 'sfpco_id');
    }
}
