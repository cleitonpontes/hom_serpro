<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class RestricaoAmparoLegal extends Model
{
    use CrudTrait;
    use LogsActivity;
    use SoftDeletes;

    protected static $logFillable = true;
    protected static $logName = 'amparo_legal_restricoes';

    protected $table = 'amparo_legal_restricoes';

    protected $fillable = [
        'tipo_restricao_id',
        'codigo_restricao'
    ];

    public function modalidade()
    {
        return $this->belongsTo(Codigoitem::class, 'tipo_restricao_id');
    }
}
