<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;
    protected $table = 'estados';
    protected $fillable = [
        'sigla',
        'nome',
        'regiao_id',
        'latitude',
        'longitude'
    ];


    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'estado_id');
    }
}
