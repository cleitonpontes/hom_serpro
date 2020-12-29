<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratoHistoricoQualificacao extends Model
{
    use LogsActivity;

    protected $table = 'contratohistoricoqualificacao';

    protected $fillable = [
        'contratohistorico_id',
        'tipo_id'
    ];

    public function contratoHistorico()
    {
        return $this->belongsTo(Contratohistorico::class, 'contratohistorico_id');
    }

    public function tiposTermoAditivo()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

}
