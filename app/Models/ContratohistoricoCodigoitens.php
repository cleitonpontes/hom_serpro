<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ContratohistoricoCodigoitens extends Model
{
    use LogsActivity;

    protected $table = 'contratohistoricoqualificacao';
    protected $primaryKey = ['contratohistorico_id', 'tipo_id'];

    protected $fillable = [
        'contratohistorico_id',
        'tipo_id'
    ];

    public function contratohistoricos()
    {
        return $this->belongsTo(Contratohistorico::class, 'contratohistorico_id');
    }

    public function qualificacoes()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_id');
    }

}
