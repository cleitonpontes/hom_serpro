<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Estado extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $primaryKey = 'id';
    protected $table = 'estados';

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'estado_id');
    }
}
