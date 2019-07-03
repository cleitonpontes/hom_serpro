<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sfcentrocusto extends Model
{
    public $timestamps = false;

    protected $table = 'sfcentrocusto';

    protected $fillable = [
        'sfpadrao_id',
        'numseqitem',
        'codcentrocusto',
        'mesreferencia',
        'anoreferencia',
        'codugbenef',
        'codsiorg',
    ];

    public function sfpadrao()
    {
        return $this->belongsTo(SfPadrao::class, 'sfpadrao_id');
    }

    public function relComItemVlr()
    {
        return $this->hasMany(Sfrelitemvlrcc::class, 'sfcc_id');
    }


}
