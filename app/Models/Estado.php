<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;

    public $primaryKey = 'id';
    protected $table = 'estados';

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'estado_id');
    }
}
