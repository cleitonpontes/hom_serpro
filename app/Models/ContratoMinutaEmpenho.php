<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoMinutaEmpenho extends Model
{
    protected $table = 'contrato_minuta_empenho_pivot';

    protected $fillable = [
        'contrato_id',
        'minuta_empenho_id'
    ];

    public function contratos()
    {
        return $this->belongsTo(Contratos::class, 'contrato_id');
    }

    public function amparosLegais()
    {
        return $this->belongsTo(MinutaEmpenho::class, 'minuta_empenho_id');
    }
}
