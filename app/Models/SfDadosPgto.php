<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SfDadosPgto extends Model
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
    protected $table = 'sfdadospgto';

    /**
     * Campos da tabela
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'sfpadrao_id',
        'codcredordevedor',
        'vlr'
    ];


    public function sfpadrao()
    {
        return $this->belongsTo(Contratosfpadrao::class, 'sfpadrao_id');
    }

    public function sfItemRecolhimento()
    {
        return $this->hasMany(SfItemRecolhimento::class, 'sfdadospgto_id');
    }

    public function sfPreDoc()
    {
        return $this->hasOne(SfPredoc::class, 'sfdadospgto_id');
    }

    public function sfAcrescimo()
    {
        return $this->hasMany(SfAcrescimo::class, 'sfdadospgto_id');
    }
}
