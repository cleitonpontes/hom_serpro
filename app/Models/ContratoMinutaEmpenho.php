<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratoMinutaEmpenho extends Model
{
    use LogsActivity;

    protected $table = 'contrato_minuta_empenho_pivot';

    public $timestamps = false;

    protected $fillable = [
        'contrato_id',
        'minuta_empenho_id'
    ];

    public function contratos()
    {
        return $this->belongsTo(Contratos::class, 'contrato_id');
    }

    public function minutasempenho()
    {
        return $this->belongsTo(MinutaEmpenho::class, 'minuta_empenho_id');
    }
}
