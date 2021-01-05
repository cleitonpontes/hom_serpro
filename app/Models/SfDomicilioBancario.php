<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfDomicilioBancario extends Model
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
    protected $table = 'sfdomiciliobancario';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpredoc_id',
        'banco',
        'agencia',
        'conta',
        'tipo'
    ];

    public function sfpredoc()
    {
        return $this->belongsTo(SfPredoc::class, 'sfpredoc_id');
    }

}
