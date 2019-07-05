<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sfrelitemvlrcc extends Model
{
    public $timestamps = false;

    protected $table = 'sfrelitemvlrcc';

    protected $fillable = [
        'sfcc_id',
        'numseqpai',
        'numseqitem',
        'codnatdespdet',
        'vlr',
        'tipo',
    ];

    public function centroCusto()
    {
        return $this->belongsTo(Sfcentrocusto::class, 'sfcc_id');
    }
}
