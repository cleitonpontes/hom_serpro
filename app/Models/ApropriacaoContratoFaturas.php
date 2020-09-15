<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApropriacaoContratoFaturas extends Model
{
    protected $table = 'apropriacoes_faturas_contratofaturas';
    protected $primaryKey = ['apropriacoes_faturas_id', 'contratofaturas_id'];
    protected $fillable = [
        'apropriacoes_faturas_id',
        'contratofaturas_id'
    ];

    public $incrementing = false;
    public $timestamps = false;

    public function apropriacao()
    {
        return $this->belongsTo('App\Models\ApropriacaoFaturas', 'apropriacoes_faturas_id');
    }

    public function fatura()
    {
        return $this->belongsTo('App\Models\Contratofatura', 'contratofaturas_id');
    }
}
