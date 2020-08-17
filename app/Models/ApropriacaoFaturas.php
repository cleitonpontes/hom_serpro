<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApropriacaoFaturas extends Model
{
    protected $table = 'apropriacoes_faturas';

    public function faturas()
    {
        return $this->belongsToMany(
            'App\Models\Contratofatura',
            'apropriacoes_faturas_contratofaturas',
            'apropriacoes_faturas_id',
            'contratofaturas_id'
        );
    }
}
