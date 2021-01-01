<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratoHistoricoMinutaEmpenho extends Model
{
    use LogsActivity;

    protected $table = 'contrato_historico_minuta_empenho';

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



