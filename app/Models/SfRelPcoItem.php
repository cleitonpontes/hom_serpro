<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfRelPcoItem extends Model
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
    protected $table = 'sfrelpcoitem';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfded_id',
        'sfdespesaanularitem_id',
        'numseqpai',
        'numseqitem',
        'vlr'
    ];

    public function sfdeducao()
    {
        return $this->belongsTo(SfDeducao::class, 'sfded_id');
    }

    public function sfdespesaanularitem()
    {
        return $this->belongsTo(SfDespesaAnularItem::class, 'sfdespesaanularitem_id');
    }
}
