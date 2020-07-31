<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use SoftDeletes;
    protected $table = 'municipios';
    protected $fillable = [
        'codigo_ibge',
        'nome',
        'latitude',
        'longitude',
        'capital',
        'estado_id'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class,'estado_id','id');
    }
}
