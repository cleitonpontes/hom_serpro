<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Sfrelitemvlrcc extends Model
{
    use LogsActivity;

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
        return $this->belongsTo(SfCentroCusto::class, 'sfcc_id');
    }
}
