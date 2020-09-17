<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use SoftDeletes;

    public $primaryKey = 'id';
    protected $table = 'municipios';

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }
}
