<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoHistoricoMinutaEmpenho extends Model
{
    protected $table = 'contrato_minuta_empenho_pivot';

    public $timestamps = false;

    protected $fillable = [
        'contrato_historico_id',
        'minuta_empenho_id'
    ];

    public function contratohistorico()
    {
        return $this->belongsTo(Contratohistorico::class, 'contrato_historico_id');
    }

    public function minutasempenho()
    {
        return $this->belongsTo(MinutaEmpenho::class, 'minuta_empenho_id');
    }
}



