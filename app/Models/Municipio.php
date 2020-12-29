<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Municipio extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $primaryKey = 'id';
    protected $table = 'municipios';

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id', 'id');
    }
}
